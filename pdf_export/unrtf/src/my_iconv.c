/*----------------------------------------------------------------------
 * Module name:  my_iconv
 * Author name:  Arkadiusz Firus
 * Create date:  07 Sep 08
 * Purpose:    iconv handles
 *----------------------------------------------------------------------
 Changes:
 * 04 Jan 10, daved@physiol.usyd.edu.au: use path specfied with -P to
 *	load charmap file(s)
 *--------------------------------------------------------------------*/

#include <stdio.h>
#include <string.h>

#include "malloc.h"
#include "my_iconv.h"
#include "util.h"
#if 1 /* daved 0.21.1 */
#include "unicode.h"
#include "path.h"
#include <unistd.h>
#include <stdlib.h>
#endif

my_iconv_t
my_iconv_open(const char *tocode, const char *fromcode)
{
	char *path;
	FILE *f;
	my_iconv_t cd = MY_ICONV_T_CLEAR;
	int c, i;

	if ((cd.desc = iconv_open(tocode, fromcode)) == (iconv_t) -1)
	{
#if 1 /* daved 0.21.1 */
		struct path_dir *path_dir_p;
		char u_success=0;
		for(path_dir_p = &topdir; path_dir_p->dir_name; path_dir_p = path_dir_p->next)
		{
			char *p;
			p = path_dir_p->dir_name;
			if(*(p+strlen(p)-1) != '/')
				p = concatenate(p, "/");
			p = concatenate(p, fromcode);
			path=concatenate(p, ".charmap");
			if(access(path, F_OK|R_OK))
				continue;
			u_success++;
			break;
		}
		if(!u_success)
		{
#else
		path = concatenate(CHARMAP_DIR, fromcode);
#endif
#if 1 /* daved 0.21.1 */
		}
		if((f = fopen(path, "r")) == NULL)
			fprintf(stderr, "failed to open charmap file %s\n", path);
#else
		f = fopen(path, "r");
#endif

		if (f != NULL)
		{
			cd.char_table = (char **)my_malloc(char_table_size * sizeof(char *));
			c = fgetc(f);

			for (i = 0; i < char_table_size && c != EOF; i++)
			{
				if (c == '<')
					cd.char_table[i] = get_unicode_char(f);
				leave_line(f);
				c = fgetc(f);
			}

			fclose(f);
		}

		my_free(path);
	}

	return cd;
}

size_t
my_iconv(my_iconv_t cd, char **inbuf, size_t *inbytesleft, char **outbuf, size_t *outbytesleft)
{
	int c, i;
	size_t result = 0;

	if (cd.desc == (iconv_t) -1) {
		if (cd.char_table != NULL)
		{
			while (*inbytesleft > 0 && *outbytesleft > 0)
			{
				c = (int) **inbuf;
				if (c < 0)
					c = 256 + c;

				if (cd.char_table[c] != NULL)
				{
					for (i = 0; cd.char_table[c][i] != '\0' && *outbytesleft > 0; i++)
					{
						**outbuf = cd.char_table[c][i];
						(*outbytesleft)--;
						(*outbuf)++;
					}
				}

				(*inbuf)++;
				(*inbytesleft)--;
				result++;
			}
		}
	}
	else
		result = iconv(cd.desc, inbuf, inbytesleft, outbuf, outbytesleft);

	return result;
}

my_iconv_t
my_iconv_close(my_iconv_t cd)
{
	int i;

	if (cd.char_table != NULL)
	{
		for (i = 0; i < char_table_size; i++)
		{
			my_free(cd.char_table[i]);
		}

		my_free((void *)cd.char_table);
		cd.char_table = NULL;
	}

	if (cd.desc != (iconv_t) -1)
	{
		iconv_close(cd.desc);
		cd.desc = (iconv_t) -1;
	}

	return cd;
}

int 
my_iconv_is_valid (my_iconv_t cd)
{
	if (cd.desc != (iconv_t) -1 || cd.char_table != NULL)
		return 1;

	return 0;
}

void
my_iconv_t_make_invalid(my_iconv_t *cd)
{
	cd->desc = (iconv_t) -1;
	cd->char_table = NULL;
}

