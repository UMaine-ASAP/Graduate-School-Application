# executable test scripts to perform
TESTS = test_html.sh test_latex.sh 

# the data set for the tests
testdata = Greek1.rtf Greek2.rtf  \
	ascii_decimal.rtf \
	backslash.rtf \
	borders_shading.rtf \
	braces.rtf \
	character_formatting.rtf \
	chars.rtf \
	columns.rtf \
	font_size.rtf \
	font_styles.rtf \
	lists.rtf \
	paragraph_formatting.rtf \
	symbol.rtf \
	text_color.rtf \
	text_formatting.rtf \
	unicode4.rtf

# the result set for the html test
testresults_html = Greek1.rtf.html Greek2.rtf.html  \
	ascii_decimal.rtf.html \
        backslash.rtf.html \
        borders_shading.rtf.html \
        braces.rtf.html \
        character_formatting.rtf.html \
        chars.rtf.html \
        columns.rtf.html \
        font_size.rtf.html \
        font_styles.rtf.html \
        lists.rtf.html \
        paragraph_formatting.rtf.html \
        symbol.rtf.html \
        text_color.rtf.html \
        text_formatting.rtf.html \
        unicode4.rtf.html

# the result set for the latex test
testresults_tex = Greek1.rtf.tex Greek2.rtf.tex  \
	ascii_decimal.rtf.tex \
        backslash.rtf.tex \
        borders_shading.rtf.tex \
        braces.rtf.tex \
        character_formatting.rtf.tex \
        chars.rtf.tex \
        columns.rtf.tex \
        font_size.rtf.tex \
        font_styles.rtf.tex \
        lists.rtf.tex \
        paragraph_formatting.rtf.tex \
        symbol.rtf.tex \
        text_color.rtf.tex \
        text_formatting.rtf.tex \
        unicode4.rtf.tex

# need to wipe out test results on make clean
CLEANFILES = $(testresults_html) $(testresults_tex)

# add the test scripts and the test data to the distributed files
EXTRA_DIST = $(TESTS) $(testdata)

TESTS_ENVIRONMENT = UNRTF=$(top_builddir)/src/unrtf

# ensure that the distributed scripts are executable before distribution.
# Could be necessary if checking out CVS.
dist-hook:
	cd $(distdir); for i in $(TESTS); do chmod +x $$i; done
