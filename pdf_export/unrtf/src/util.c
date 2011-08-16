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
 * Module name:    util
 * Author name:    Zachary Smith
 * Create date:    01 Aug 01
 * Purpose:        Utility functions.
 *----------------------------------------------------------------------
 * Changes:
 * 22 Sep 01, tuorfa@yahoo.com: added function-level comment blocks 
 * 29 Mar 05, daved@physiol.usyd.edu.au: changes requested by ZT Smith
 * 16 Dec 07, daved@physiol.usyd.edu.au: updated to GPL v3
 * 09 Nov 08, arkadiusz.firus@gmail.com: added leave_line
 *--------------------------------------------------------------------*/

#ifdef HAVE_CONFIG_H
#include <config.h>
#endif

#ifdef HAVE_STDLIB_H
#include <stdlib.h>
#endif

#ifdef HAVE_CTYPE_H
#include <ctype.h>
#endif

#ifdef HAVE_STRING_H
#include <string.h>

#ifdef HAVE_STDIO_H
#include <stdio.h>
#endif


#include "malloc.h"
#endif

/*========================================================================
 * Name:	h2toi
 * Purpose:	Converts a 2-digit hexadecimal value to an unsigned integer.
 * Args:	String.
 * Returns:	Integer.
 *=======================================================================*/

/* Convert a two-char hexadecimal expression to an integer */
int
h2toi (char *s) {
	int tmp;
	int ch;
	tmp = tolower(*s++);
	if (tmp>'9') tmp-=('a'-10); 
	else tmp-='0';
	ch=16*tmp;
	tmp = tolower(*s++);
	if (tmp>'9') tmp-=('a'-10); 
	else tmp-='0';
	ch+=tmp;
	return ch;
}

/*========================================================================
 * Name		leave_line
 * Purpose:	Read characters form file until '\n' (or EOF) is found.
 * Args:	File to read from.
 * Returns:	Nothing
 *=======================================================================*/

void
leave_line (FILE *f)
{
	int c;

	do
	{
		c = fgetc(f);
	} while (c != '\n' && c != EOF);

	return;
}

/*========================================================================
 * Name:	concatenate
 * Purpose:	Returns new string made from concatenation of two arguments
 * Args:	Two strings.
 * Returns:	String.
 *=======================================================================*/
char *
concatenate (const char *s1, const char *s2)
{
	char *result;

	result = my_malloc((strlen(s1) + strlen(s2) + 1) * sizeof(char));
	strcpy(result, s1);
	strcat(result, s2);

	return result;
}
