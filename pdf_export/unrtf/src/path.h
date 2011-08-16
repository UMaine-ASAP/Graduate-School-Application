#define DEFAULT_UNRTF_SEARCH_PATH	"/usr/local/lib/unrtf/"

char	*search_path;
int	n_path_dirs;
int	path_checked;

struct path_dir
{
	char *dir_name;
	struct path_dir *next;
};

struct path_dir topdir;

int	check_dirs();
void	show_dirs();
