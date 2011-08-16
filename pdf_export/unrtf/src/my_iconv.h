/*----------------------------------------------------------------------
 * Module name:  my_iconv.h
 * Author name:  Arkadiusz Firus
 * Create date:  01 Jul 08
 * Purpose:    my_conv definitions
 *--------------------------------------------------------------------*/

#ifndef HAVE_ICONV_H
#include <iconv.h>
#define HAVE_ICONV_H
#endif

#define CHARMAP_DIR "/usr/local/lib/unrtf/charmaps/"
#define char_table_size 256

typedef struct
{
	iconv_t desc;
	char **char_table;
} my_iconv_t;

#define MY_ICONV_T_CLEAR {(iconv_t) -1, NULL}

my_iconv_t my_iconv_open(const char *tocode, const char *fromcode);

size_t my_iconv(my_iconv_t cd, char **inbuf, size_t *inbytesleft, char **outbuf, size_t *outbytesleft);

my_iconv_t my_iconv_close(my_iconv_t cd);

int my_iconv_is_valid(my_iconv_t cd);

void my_iconv_t_make_invalid(my_iconv_t *cd);

