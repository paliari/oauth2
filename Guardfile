# A sample Guardfile
# More info at https://github.com/guard/guard#readme
# gem uninstall guard-phpunit && gem install guard-phpunit2

guard 'phpunit2', :tests_path => 'tests' do
  watch(%r{^.+Test\.php$})
  watch(%r{src/Paliari/Oauth2/(.+).php}) {|m| "tests/#{m[1]}Test.php"}
end
