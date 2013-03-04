watch("(.*).php") do |match|
  run_tests %{Tests/#{match[1]}Test.php}
end

watch("(.*).feature") do |match|
  run_tests %{Tests/#{match[1]}Test.php}
end


watch("tests/.*Test.php") do |match|
  run_tests match[0]
end


# def run_test(file)
#   unless File.exist?(file)
#     puts "#{file} does not exist"
#     return
#   end
#   puts "Running #{file}"
#   result = `phpunit #{file}`
#   puts result
# end

def run_tests(file)
  result = `cucumber tests/features/home_pages.feature`

  if result.match(/(failed)/) #AssertionFailedError
    result = "Errors: \n" + result
    notify "Test Failed", result, "tests/failure.png", 6000
  else
    result = "Success: \n" + result
    notify "Test Succeeded", result, "tests/success.png", 6000
  end
  puts result
end
#   clear_console
#   unless File.exist?(file)
#     puts "#{file} does not exist"
#     notify "command", "Test file: #{file} not found!", "failure.png", 6000
#     return
#   end
#   puts "Running #{file}"
#   result = `phpunit #{file}`
#   puts result
#   if result.match(/OK/)
#     notify "#{file}", "Tests Passed Successfuly", "success.png", 2000
#   elsif result.match(/FAILURES\!/)
#     notify_failed file, result
#   else
#   	notify "command not found" "not found"
#   end
# end

# def notify_failed cmd, result
#   failed_examples = result.scan(/failure:\n\n(.*)\n/)
#   notify "#{cmd}", failed_examples[0], "failure.png", 6000
# end


def clear_console
  #puts "\e[H\e[2J"  #clear console
end

def notify title, msg, img, show_time
  #images_dir='~/.autotest/images'
  system "growlnotify -t '#{title}' -m \"#{msg}\" --image '#{img}' " #{images_dir}/#{img} -t #{show_time}"
end