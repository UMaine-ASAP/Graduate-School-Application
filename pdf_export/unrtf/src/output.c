/*=============================================================================
   GNU UnRTF, a command-line program to convert RTF documents to other formats.
   Copyright (C) 2000,2001,2004 by Zachary Smith

   This program is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 3 of the License, or
   (at your option) any later version.

   This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with this program; if not, write to the Free Software
   Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA

   The maintainer is reachable by electronic mail at daved@physiol.usyd.edu.au
=============================================================================*/


/*----------------------------------------------------------------------
 * Module name:    output
 * Author name:    Zachary Smith
 * Create date:    18 Sep 01
 * Purpose:        Generalized output module
 *----------------------------------------------------------------------
 * Changes:
 * 22 Sep 01, tuorfa@yahoo.com: addition of functions to change font size
 * 22 Sep 01, tuorfa@yahoo.com: added function-level comment blocks 
 * 08 Oct 03, daved@physiol.usyd.edu.au: added stdlib.h for linux
 * 25 Sep 04, st001906@hrz1.hrz.tu-darmstadt.de: added stdlib.h for djgpp
 * 29 Mar 05, daved@physiol.usyd.edu.au: changes requested by ZT Smith
 * 06 Jan 06, marcossamaral@terra.com.br: changes in STDOUT   
 * 16 Dec 07, daved@physiol.usyd.edu.au: updated to GPL v3
 * 17 Dec 07, daved@physiol.usyd.edu.au: added support for --noremap from
 *		David Santinoli
 * 09 Nov 08, arkadiusz.firus@gmail.com: use iconv
 *--------------------------------------------------------------------*/


#ifdef HAVE_CONFIG_H
#include <config.h>
#endif

#ifdef HAVE_STDIO_H
#include <stdio.h>
#endif

#ifdef HAVE_STDLIB_H
#include <stdlib.h>
#endif

#ifdef HAVE_STRING_H
#include <string.h>
#endif

#include "malloc.h"
#include "defs.h"
#include "error.h"
#include "output.h"
#include "main.h"
#include "convert.h"


#ifndef HAVE_ATTR_H
#include "attr.h"
#define HAVE_ATTR_H
#endif

/*========================================================================
 * Name:	op_create
 * Purpose:	Creates a blank output personality.
 * Args:	None.
 * Returns:	Output personality struct.
 *=======================================================================*/

OutputPersonality*
op_create ()
{
	OutputPersonality* new_op;

	new_op = (OutputPersonality*) my_malloc (sizeof(OutputPersonality));
	if (!new_op)
		error_handler ("cannot allocate output personality");

	memset ((void*) new_op, 0, sizeof (OutputPersonality));
	return new_op;
}

/*========================================================================
 * Name:	op_free
 * Purpose:	Deallocates an output personality, but none of the strings
 *		it points to since they are usually constants.
 * Args:	OutputPersonality.
 * Returns:	None.
 *=======================================================================*/

void
op_free (OutputPersonality *op)
{
	CHECK_PARAM_NOT_NULL(op);

	my_free ((void*) op);
}




/*========================================================================
 * Name:	op_translate_char
 * Purpose:	Performs a translation of a character in the context of
 *		a given output personality.
 * Args:	OutputPersonality, character set#, character.
 * Returns:	String.
 *=======================================================================*/

char *
op_translate_char (OutputPersonality *op, my_iconv_t cd, int ch)
{
	short start;
	char *result=NULL;
	static char output_buffer[2]={ 0, 0 };
	char *inbuf, *outbuf;
	size_t inbytes = (ch / 256) + 1, outbytes = inbytes * 4, i;

	CHECK_PARAM_NOT_NULL(op);

	if (no_remap_mode == TRUE && ch < 256)
	{
		output_buffer[0]=ch;
		result=output_buffer;
	}
	else
	if (result == NULL)
	{
		inbuf = my_malloc(inbytes + 1);
		outbuf = my_malloc(outbytes + 1);

		for (i = inbytes - 1; ch > 255; i--)
		{
			inbuf[i] = ch % 256;
			ch /= 256;
		}

		inbuf[0] = ch;
		inbuf[inbytes] = '\0';
		i = outbytes;
		if (!my_iconv_is_valid(cd))
		{
			cd = my_iconv_open("UTF-8", "cp1252");

			if (my_iconv(cd, &inbuf, &inbytes, &outbuf, &outbytes) == -1)
			{
				fprintf(stderr, "unrtf: Error in executing iconv1\n");
				return NULL;
			}
			
			my_iconv_close(cd);			
		}
		else

			if (my_iconv(cd, &inbuf, &inbytes, &outbuf, &outbytes) == -1)
			{
				fprintf(stderr, "unrtf: Error in executing iconv\n");
				return NULL;
			}
		*outbuf = '\0';
		outbuf -= i - outbytes;
/* Conversion from string to utf8 code number */
		inbytes = 0;

		for (i = 0; outbuf[i] != '\0'; i++)
			inbytes++;

		ch = 0;

		for (i = 0; i < inbytes; i++)
		{
			if (i == 0)
				switch (inbytes)
				{
					case 1:
						ch =  outbuf[0];
						break;
					case 2:
						ch = (unsigned char) outbuf[0] - 192;
						break;
					case 3:
						ch = (unsigned char) outbuf[0] - 224;
						break;
					case 4:
						ch = (unsigned char) outbuf[0] - 240;
						break;
				}
			else
				ch = (ch * 64) + ((unsigned char) outbuf[i] - 128);
		}
/* End of conversion*/

		result = get_alias(op, ch);

		if (result == NULL)
			if (ch > 127 && op->unisymbol_print)
				result = assemble_string(op->unisymbol_print, ch);
			else
				result = outbuf;
	}

	return result;
}


/*========================================================================
 * Name:	op_begin_std_fontsize 
 * Purpose:	Prints whatever is necessary to perform a change in the
 *		current font size.
 * Args:	OutputPersonality, desired size.
 * Returns:	None.
 *=======================================================================*/

void
op_begin_std_fontsize (OutputPersonality *op, int size)
{
	int found_std_expr = FALSE;

	CHECK_PARAM_NOT_NULL(op);

	/* Look for an exact match with a standard point size.
	 */
	switch (size) {
	case 8:
		if (op->fontsize8_begin) {
			if (safe_printf(0, op->fontsize8_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize8_begin");
			found_std_expr = TRUE;
		}
		break;
	case 10:
		if (op->fontsize10_begin) {
			if (safe_printf(0, op->fontsize10_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize10_begin");
			found_std_expr = TRUE;
		}
		break;
	case 12:
		if (op->fontsize12_begin) {
			if (safe_printf(0, op->fontsize12_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize12_begin");
			found_std_expr = TRUE;
		}
		break;
	case 14:
		if (op->fontsize14_begin) {
			if (safe_printf(0, op->fontsize14_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize14_begin");
			found_std_expr = TRUE;
		}
		break;
	case 18:
		if (op->fontsize18_begin) {
			if (safe_printf(0, op->fontsize18_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize18_begin");
			found_std_expr = TRUE;
		}
		break;
	case 24:
		if (op->fontsize24_begin) {
			if (safe_printf(0, op->fontsize24_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize24_begin");
			found_std_expr = TRUE;
		}
		break;
	case 36:
		if (op->fontsize36_begin) {
			if (safe_printf(0, op->fontsize36_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize36_begin");
			found_std_expr = TRUE;
		}
		break;
	case 48:
		if (op->fontsize48_begin) {
			if (safe_printf(0, op->fontsize48_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize48_begin");
			found_std_expr = TRUE;
		}
		break;
	}

	/* If no exact match, try to write out a change to the
	 * exact point size.
	 */
	if (!found_std_expr) {
		if (op->fontsize_begin) {
			char expr[16];
			sprintf (expr, "%d", size);
			if (safe_printf (1, op->fontsize_begin, expr)) fprintf(stderr, TOO_MANY_ARGS, "fontsize_begin");
		} else {
			/* If we cannot write out a change for the exact
			 * point size, we must approximate to a standard
			 * size.
			 */
			if (size<9 && op->fontsize8_begin) {
				if (safe_printf(0, op->fontsize8_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize8_begin");
			} else 
			if (size<11 && op->fontsize10_begin) {
				if (safe_printf(0, op->fontsize10_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize10_begin");
			} else 
			if (size<13 && op->fontsize12_begin) {
				if (safe_printf(0, op->fontsize12_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize12_begin");
			} else 
			if (size<16 && op->fontsize14_begin) {
				if (safe_printf(0, op->fontsize14_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize14_begin");
			} else 
			if (size<21 && op->fontsize18_begin) {
				if (safe_printf(0, op->fontsize18_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize18_begin");
			} else 
			if (size<30 && op->fontsize24_begin) {
				if (safe_printf(0, op->fontsize24_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize24_begin");
			} else 
			if (size<42 && op->fontsize36_begin) {
				if (safe_printf(0, op->fontsize36_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize36_begin");
			} else 
			if (size>40 && op->fontsize48_begin) {
				if (safe_printf(0, op->fontsize48_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize48_begin");
			} else 
			/* If we can't even produce a good approximation,
			 * just try to get a font size near 12 point.
			 */
			if (op->fontsize12_begin)
				if (safe_printf(0, op->fontsize12_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize12_begin");
			else
			if (op->fontsize14_begin)
				if (safe_printf(0, op->fontsize14_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize14_begin");
			else
			if (op->fontsize10_begin)
				if (safe_printf(0, op->fontsize10_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize10_begin");
			else
			if (op->fontsize18_begin)
				if (safe_printf(0, op->fontsize18_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize18_begin");
			else
			if (op->fontsize8_begin)
				if (safe_printf(0, op->fontsize8_begin)) fprintf(stderr, TOO_MANY_ARGS, "fontsize8_begin");
			else
				error_handler ("output personality lacks sufficient font size change capability");
		}
	}
}


/*========================================================================
 * Name:	op_end_std_fontsize 
 * Purpose:	Prints whatever is necessary to perform a change in the
 *		current font size.
 * Args:	OutputPersonality, desired size.
 * Returns:	None.
 *=======================================================================*/

void
op_end_std_fontsize (OutputPersonality *op, int size)
{
	int found_std_expr = FALSE;

	CHECK_PARAM_NOT_NULL(op);

	/* Look for an exact match with a standard point size.
	 */
	switch (size) {
	case 8:
		if (op->fontsize8_end) {
			if (safe_printf(0, op->fontsize8_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize8_end");
			found_std_expr = TRUE;
		}
		break;
	case 10:
		if (op->fontsize10_end) {
			if (safe_printf(0, op->fontsize10_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize10_end");
			found_std_expr = TRUE;
		}
		break;
	case 12:
		if (op->fontsize12_end) {
			if (safe_printf(0, op->fontsize12_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize12_end");
			found_std_expr = TRUE;
		}
		break;
	case 14:
		if (op->fontsize14_end) {
			if (safe_printf(0, op->fontsize14_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize14_end");
			found_std_expr = TRUE;
		}
		break;
	case 18:
		if (op->fontsize18_end) {
			if (safe_printf(0, op->fontsize18_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize18_end");
			found_std_expr = TRUE;
		}
		break;
	case 24:
		if (op->fontsize24_end) {
			if (safe_printf(0, op->fontsize24_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize24_end");
			found_std_expr = TRUE;
		}
		break;
	case 36:
		if (op->fontsize36_end) {
			if (safe_printf(0, op->fontsize36_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize36_end");
			found_std_expr = TRUE;
		}
		break;
	case 48:
		if (op->fontsize48_end) {
			if (safe_printf(0, op->fontsize48_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize48_end");
			found_std_expr = TRUE;
		}
		break;
	}

	/* If no exact match, try to write out a change to the
	 * exact point size.
	 */
	if (!found_std_expr) {
		if (op->fontsize_end) {
			char expr[16];
			sprintf (expr, "%d", size);
			if (safe_printf(1, op->fontsize_end, expr)) fprintf(stderr, TOO_MANY_ARGS, "fontsize_end");
		} else {
			/* If we cannot write out a change for the exact
			 * point size, we must approximate to a standard
			 * size.
			 */
			if (size<9 && op->fontsize8_end) {
				if (safe_printf(0, op->fontsize8_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize8_end");
			} else 
			if (size<11 && op->fontsize10_end) {
				if (safe_printf(0, op->fontsize10_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize10_end");
			} else 
			if (size<13 && op->fontsize12_end) {
				if (safe_printf(0, op->fontsize12_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize12_end");
			} else 
			if (size<16 && op->fontsize14_end) {
				if (safe_printf(0, op->fontsize14_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize14_end");
			} else 
			if (size<21 && op->fontsize18_end) {
				if (safe_printf(0, op->fontsize18_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize18_end");
			} else 
			if (size<30 && op->fontsize24_end) {
				if (safe_printf(0, op->fontsize24_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize24_end");
			} else 
			if (size<42 && op->fontsize36_end) {
				if (safe_printf(0, op->fontsize36_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize36_end");
			} else 
			if (size>40 && op->fontsize48_end) {
				if (safe_printf(0, op->fontsize48_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize48_end");
			} else 
			/* If we can't even produce a good approximation,
			 * just try to get a font size near 12 point.
			 */
			if (op->fontsize12_end)
				if (safe_printf(0, op->fontsize12_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize12_end");
			else
			if (op->fontsize14_end)
				if (safe_printf(0, op->fontsize14_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize14_end");
			else
			if (op->fontsize10_end)
				if (safe_printf(0, op->fontsize10_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize10_end");
			else
			if (op->fontsize18_end)
				if (safe_printf(0, op->fontsize18_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize18_end");
			else
			if (op->fontsize8_end)
				if (safe_printf(0, op->fontsize8_end)) fprintf(stderr, TOO_MANY_ARGS, "fontsize8_end");
			else
				error_handler ("output personality lacks sufficient font size change capability");
		}
	}
}

#if 1 /* AK3 - AF */
/*========================================================================
 * Name:	add_alias
 * Purpose:	Adds alias (text) for a char number nr.
 * Args:	OutputPersonality, char's number, alias.
 * Returns:	None.
 *=======================================================================*/

void
add_alias(OutputPersonality *op, int nr, char *text)
{
	op->aliases = (Aliases *)add_to_collection((Collection *)op->aliases, nr, text);
}

/*========================================================================
 * Name:	get_alias
 * Purpose:	Search for alias for character number nr.
 * Args:	OutputPersonality, char's number.
 * Returns:	Text alias or NULL if found nothing.
 *=======================================================================*/

char *
get_alias(OutputPersonality *op, int nr)
{
	return get_from_collection((Collection *)op->aliases, nr);
}
#endif

