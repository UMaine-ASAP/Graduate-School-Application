/*----------------------------------------------------------------------
 * Module name:    unicode
 * Author name:    Arkadiusz Firus
 * Create date:    09 Nov 08
 * Purpose:        unicode translations
 *----------------------------------------------------------------------
 * Changes:
 * 04 Jan 10, daved@physiol.usyd.edu.au: null terminate strings in
 *		unicode_to_string
 *--------------------------------------------------------------------*/
#include <stdio.h>

#include "malloc.h"

/*========================================================================
 * Name		get_unicode
 * Purpose:	Translates unicode character to number.
 * Args:	Unicode character.
 * Returns:	Unicode number.
 *=======================================================================*/

int
get_unicode(char *string)
{
	int uc = 0, i;

	for (i = 0; string[i] != '\0'; i++)
	{
		if (string[i] > 47 && string[i] < 58)
		{
			uc = (uc * 16) + string[i] - 48;
		}

		if (string[i] > 64 && string[i] < 71)
		{
			uc = (uc * 16) + string[i] - 55;
		}
	}

	return uc;
}

/*========================================================================
 * Name		unicode_to_string
 * Purpose:	Translates unicode number to character.
 * Args:	Unicode number.
 * Returns:	Unicode character.
 *=======================================================================*/
char *
unicode_to_string(int uc)
{
	char *string;

	if (uc < 0x7f)
	{
		string = my_malloc(2 * sizeof(char));
		string[0] = (unsigned char) uc;
		string[1] = '\0';
	}
	else if (uc < 0x7ff)
	{
		string = my_malloc(3 * sizeof(char));
		string[0] = (unsigned char) 192 + (uc / 64);
		string[1] = (unsigned char) 128 + (uc % 64);
		string[2] = '\0';
	}
	else if (uc < 0xffff)
	{
		string = my_malloc(4 * sizeof(char));
		string[0] = (unsigned char) 224 + (uc / (64 * 64));
		string[1] = (unsigned char) 128 + ((uc / 64) % 64);
		string[2] = (unsigned char) 128 + (uc % 64);
		string[3] = '\0';
	}
	else if (uc < 0x1FFFFF)
	{
		string = my_malloc(5 * sizeof(char));
		string[0] = (unsigned char) 240 + (uc / (64 * 64 * 64));
		string[1] = (unsigned char) 128 + ((uc / (64 * 64)) % 64);
		string[2] = (unsigned char) 128 + ((uc / 64) % 64);
		string[3] = (unsigned char) 128 + (uc % 64);
		string[4] = '\0';
	}
	else if (uc < 0x3FFFFFF)
	{
		string = my_malloc(6 * sizeof(char));
		string[0] = (unsigned char) 248 + (uc / (64 * 64 * 64 * 64));
		string[1] = (unsigned char) 128 + ((uc / (64 * 64 * 64)) % 64);
		string[2] = (unsigned char) 128 + ((uc / (64 * 64)) % 64);
		string[3] = (unsigned char) 128 + ((uc / 64) % 64);
		string[4] = (unsigned char) 128 + (uc % 64);
		string[5] = '\0';
	}
	else if (uc < 0x7FFFFFFF)
	{
		string = my_malloc(7 * sizeof(char));
		string[0] = (unsigned char) 252 + (uc / (64 * 64 * 64 * 64 * 64));
		string[1] = (unsigned char) 128 + ((uc / (64 * 64 * 64 * 64)) % 64);
		string[2] = (unsigned char) 128 + ((uc / (64 * 64 * 64)) % 64);
		string[3] = (unsigned char) 128 + ((uc / (64 * 64)) % 64);
		string[4] = (unsigned char) 128 + ((uc / 64) % 64);
		string[5] = (unsigned char) 128 + (uc % 64);
		string[6] = '\0';
	}

	return string;
}

/*========================================================================
 * Name		get_unicode_char
 * Purpose:	Reads unicode character (in format <UN...N> and translates
		it to printable unicode character.
 * Caution:	This function should be executed after char '<'  was read.
		It reads until char '>' was found or EOL or EOF.
 * Args:	File to read from.
 * Returns:	Unicode character.
 *=======================================================================*/

char *
get_unicode_char(FILE *file)
{
	int allocated = 5, len = 0, uc;
	char c, *unicode_number = my_malloc(allocated * sizeof(char));

	c = fgetc(file);

	while (c != '>' && c != '\n' && c != EOF)
	{
		unicode_number[len] = c;
		c = fgetc(file);
		len++;

		if (len == allocated)
		{
			allocated *= 2;
			unicode_number = my_realloc(unicode_number, allocated / 2, allocated);
		}
	}

	if (c != '>')
		ungetc(c, file);

	unicode_number[len] = '\0';
	uc = get_unicode(unicode_number);

	return unicode_to_string(uc);
}
