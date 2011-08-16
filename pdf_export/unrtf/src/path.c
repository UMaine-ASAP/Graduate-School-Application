#include <stdio.h>
#include <string.h>
#include <unistd.h>
#include <stdlib.h>

#include "path.h"

struct path_dir *path_dir_p = &topdir;

int check_dirs()
{
	char *p;
	char *colon;
	char *dir_name;
	int a;

	for(p = search_path; *p != '\0';)
	{
		dir_name = p;
		if((colon = strchr(p, ':')) != NULL)
		{
			p = colon;
			*p++ = '\0';
		}
		else
			p += strlen(p);
		a = access(dir_name, R_OK|X_OK);
		if(a)
			continue;
		path_dir_p->dir_name = dir_name;
		if((path_dir_p->next = (struct path_dir *)malloc(sizeof (struct path_dir))) == NULL)
		{
			fprintf(stderr,"cannot malloc\n");
			exit(1);
		}
		path_dir_p = path_dir_p->next;
		path_dir_p->dir_name = 0;
		n_path_dirs++;
	}
	path_checked = 1;
	return(n_path_dirs);
}

void show_dirs()
{
	if(n_path_dirs == 0)
	{
		fprintf(stderr,"no directories to show\n");
		exit(1);
	}
	fprintf(stderr,"show_dirs: %d directories\n", n_path_dirs);
	for(path_dir_p = &topdir; path_dir_p->dir_name; path_dir_p = path_dir_p->next)
		fprintf(stderr,"directory = %s\n", path_dir_p->dir_name);
}

