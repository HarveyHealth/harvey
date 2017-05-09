a!/bin/bash
if [[ -s //etc/profile ]]; then
  source //etc/profile
fi

if [[ -s $HOME/.bash_profile ]] ; then
  source $HOME/.bash_profile
fi

echo "source $HOME/.travis/job_stages" >> $HOME/.bashrc

mkdir -p $HOME/.travis

cat <<'EOFUNC' >>$HOME/.travis/job_stages
ANSI_RED="\033[31;1m"
ANSI_GREEN="\033[32;1m"
ANSI_RESET="\033[0m"
ANSI_CLEAR="\033[0K"

if [ $TERM = dumb ]; then
  unset TERM
fi
: "${SHELL:=/bin/bash}"
: "${TERM:=xterm}"
: "${USER:=travis}"
export SHELL
export TERM
export USER

TRAVIS_TEST_RESULT=
TRAVIS_CMD=

travis_cmd() {
  local assert output display retry timing cmd result secure

  cmd=$1
  TRAVIS_CMD=$cmd
  shift

  while true; do
    case "$1" in
      --assert)  assert=true; shift ;;
      --echo)    output=true; shift ;;
      --display) display=$2;  shift 2;;
      --retry)   retry=true;  shift ;;
      --timing)  timing=true; shift ;;
      --secure)  secure=" 2>/dev/null"; shift ;;
      *) break ;;
    esac
  done

  if [[ -n "$timing" ]]; then
    travis_time_start
  fi

  if [[ -n "$output" ]]; then
    echo "\$ ${display:-$cmd}"
  fi

  if [[ -n "$retry" ]]; then
    travis_retry eval "$cmd $secure"
    result=$?
  else
    if [[ -n "$secure" ]]; then
      eval "$cmd $secure" 2>/dev/null
    else
      eval "$cmd $secure"
    fi
    result=$?
    if [[ -n $secure && $result -ne 0 ]]; then
      echo -e "${ANSI_RED}The previous command failed, possibly due to a malformed secure environment variable.${ANSI_CLEAR}
${ANSI_RED}Please be sure to escape special characters such as ' ' and '$'.${ANSI_CLEAR}
${ANSI_RED}For more information, see https://docs.travis-ci.com/user/encryption-keys.${ANSI_CLEAR}"
    fi
  fi

  if [[ -n "$timing" ]]; then
    travis_time_finish
  fi

  if [[ -n "$assert" ]]; then
    travis_assert $result
  fi

  return $result
}

travis_time_start() {
  travis_timer_id=$(printf %08x $(( RANDOM * RANDOM )))
  travis_start_time=$(travis_nanoseconds)
  echo -en "travis_time:start:$travis_timer_id\r${ANSI_CLEAR}"
}

travis_time_finish() {
  local result=$?
  travis_end_time=$(travis_nanoseconds)
  local duration=$(($travis_end_time-$travis_start_time))
  echo -en "\ntravis_time:end:$travis_timer_id:start=$travis_start_time,finish=$travis_end_time,duration=$duration\r${ANSI_CLEAR}"
  return $result
}

travis_nanoseconds() {
  local cmd="date"
  local format="+%s%N"
  local os=$(uname)

  if hash gdate > /dev/null 2>&1; then
    
    cmd="gdate"
  elif [[ "$os" = Darwin ]]; then
    
    format="+%s000000000"
  fi

  $cmd -u $format
}

travis_internal_ruby() {
  if ! type rvm &>/dev/null; then
    source $HOME/.rvm/scripts/rvm &>/dev/null
  fi
  local i selected_ruby rubies_array rubies_array_sorted rubies_array_len
  rubies_array=( $(
    rvm list strings \
      | while read -r v; do
          if [[ ! "${v}" =~ ^ruby-(2\.[0-2]\.[0-9]|1\.9\.3) ]]; then
            continue
          fi
          v="${v//ruby-/}"
          v="${v%%-*}"
          echo "$(vers2int "${v}")_${v}"
        done
  ) )
  bash_qsort_numeric "${rubies_array[@]}"
  rubies_array_sorted=( ${bash_qsort_numeric_ret[@]} )
  rubies_array_len="${#rubies_array_sorted[@]}"
  i=$(( rubies_array_len - 1 ))
  selected_ruby="${rubies_array_sorted[${i}]}"
  selected_ruby="${selected_ruby##*_}"
  echo "${selected_ruby:-default}"
}

travis_assert() {
  local result=${1:-$?}
  if [ $result -ne 0 ]; then
    echo -e "\n${ANSI_RED}The command \"$TRAVIS_CMD\" failed and exited with $result during $TRAVIS_STAGE.${ANSI_RESET}\n\nYour build has been stopped."
    travis_terminate 2
  fi
}

travis_result() {
  local result=$1
  export TRAVIS_TEST_RESULT=$(( ${TRAVIS_TEST_RESULT:-0} | $(($result != 0)) ))

  if [ $result -eq 0 ]; then
    echo -e "\n${ANSI_GREEN}The command \"$TRAVIS_CMD\" exited with $result.${ANSI_RESET}"
  else
    echo -e "\n${ANSI_RED}The command \"$TRAVIS_CMD\" exited with $result.${ANSI_RESET}"
  fi
}

travis_terminate() {
  pkill -9 -P $$ &> /dev/null || true
  exit $1
}

travis_wait() {
  local timeout=$1

  if [[ $timeout =~ ^[0-9]+$ ]]; then
    
    shift
  else
    
    timeout=20
  fi

  local cmd="$@"
  local log_file=travis_wait_$$.log

  $cmd &>$log_file &
  local cmd_pid=$!

  travis_jigger $! $timeout $cmd &
  local jigger_pid=$!
  local result

  {
    wait $cmd_pid 2>/dev/null
    result=$?
    ps -p$jigger_pid &>/dev/null && kill $jigger_pid
  }

  if [ $result -eq 0 ]; then
    echo -e "\n${ANSI_GREEN}The command $cmd exited with $result.${ANSI_RESET}"
  else
    echo -e "\n${ANSI_RED}The command $cmd exited with $result.${ANSI_RESET}"
  fi

  echo -e "\n${ANSI_GREEN}Log:${ANSI_RESET}\n"
  cat $log_file

  return $result
}

travis_jigger() {
  
  local cmd_pid=$1
  shift
  local timeout=$1 
  shift
  local count=0

  
  echo -e "\n"

  while [ $count -lt $timeout ]; do
    count=$(($count + 1))
    echo -ne "Still running ($count of $timeout): $@\r"
    sleep 60
  done

  echo -e "\n${ANSI_RED}Timeout (${timeout} minutes) reached. Terminating \"$@\"${ANSI_RESET}\n"
  kill -9 $cmd_pid
}

travis_retry() {
  local result=0
  local count=1
  while [ $count -le 3 ]; do
    [ $result -ne 0 ] && {
      echo -e "\n${ANSI_RED}The command \"$@\" failed. Retrying, $count of 3.${ANSI_RESET}\n" >&2
    }
    "$@"
    result=$?
    [ $result -eq 0 ] && break
    count=$(($count + 1))
    sleep 1
  done

  [ $count -gt 3 ] && {
    echo -e "\n${ANSI_RED}The command \"$@\" failed 3 times.${ANSI_RESET}\n" >&2
  }

  return $result
}

travis_fold() {
  local action=$1
  local name=$2
  echo -en "travis_fold:${action}:${name}\r${ANSI_CLEAR}"
}

decrypt() {
  echo $1 | base64 -d | openssl rsautl -decrypt -inkey $HOME/.ssh/id_rsa.repo
}

vers2int() {
  printf '1%03d%03d%03d%03d' $(echo "$1" | tr '.' ' ')
}

bash_qsort_numeric() {
   local pivot i smaller=() larger=()
   bash_qsort_numeric_ret=()
   (($#==0)) && return 0
   pivot=${1}
   shift
   for i; do
      if [[ ${i%%_*} -lt ${pivot%%_*} ]]; then
         smaller+=( "$i" )
      else
         larger+=( "$i" )
      fi
   done
   bash_qsort_numeric "${smaller[@]}"
   smaller=( "${bash_qsort_numeric_ret[@]}" )
   bash_qsort_numeric "${larger[@]}"
   larger=( "${bash_qsort_numeric_ret[@]}" )
   bash_qsort_numeric_ret=( "${smaller[@]}" "$pivot" "${larger[@]}" )
}

EOFUNC


if [[ -f /etc/apt/sources.list.d/rabbitmq-source.list ]] ; then
  sudo rm -f /etc/apt/sources.list.d/rabbitmq-source.list
fi

mkdir -p $HOME/build
cd       $HOME/build

cat <<'EOFUNC_CONFIGURE' >>$HOME/.travis/job_stages
function travis_run_configure() {

travis_fold start system_info
  echo -e "\033[33;1mBuild system information\033[0m"
  echo -e "Build language: php"
  echo -e "Build dist: trusty"
  echo -e "Build id: ''"
  echo -e "Job id: ''"
  if [[ -f /usr/share/travis/system_info ]]; then
    cat /usr/share/travis/system_info
  fi
travis_fold end system_info

echo
          if [[ -d /var/lib/apt/lists && -n $(command -v apt-get) ]]; then
            sudo rm -rf /var/lib/apt/lists/*
            for f in $(grep -l rwky/redis /etc/apt/sources.list.d/*); do
              sed 's,rwky/redis,rwky/ppa,g' $f > /tmp/${f##**/}
              sudo mv /tmp/${f##**/} /etc/apt/sources.list.d
            done
            sudo apt-get update -qq 2>&1 >/dev/null
          fi

export PATH=$(echo $PATH | sed -e 's/::/:/g')
export PATH=$(echo -n $PATH | perl -e 'print join(":", grep { not $seen{$_}++ } split(/:/, scalar <>))')
echo "options rotate
options timeout:1

nameserver 8.8.8.8
nameserver 8.8.4.4
nameserver 208.67.222.222
nameserver 208.67.220.220
" | sudo tee /etc/resolv.conf &> /dev/null
sudo sed -e 's/^\(127\.0\.0\.1.*\)$/\1 '`hostname`'/' -i'.bak' /etc/hosts
test -f ~/.m2/settings.xml && sed -i.bak -e 's|https://nexus.codehaus.org/snapshots/|https://oss.sonatype.org/content/repositories/codehaus-snapshots/|g' ~/.m2/settings.xml
sudo sed -e 's/^\([0-9a-f:]\+\) localhost/\1/' -i'.bak' /etc/hosts
test -f /etc/mavenrc && sudo sed -e 's/M2_HOME=\(.\+\)$/M2_HOME=${M2_HOME:-\1}/' -i'.bak' /etc/mavenrc
if [ $(command -v sw_vers) ]; then
  echo "Fix WWDRCA Certificate"
  sudo security delete-certificate -Z 0950B6CD3D2F37EA246A1AAA20DFAADBD6FE1F75 /Library/Keychains/System.keychain
  wget -q https://developer.apple.com/certificationauthority/AppleWWDRCA.cer
  sudo security add-certificates -k /Library/Keychains/System.keychain AppleWWDRCA.cer
fi

grep '^127\.0\.0\.1' /etc/hosts | sed -e 's/^127\.0\.0\.1 \(.*\)/\1/g' | sed -e 's/localhost \(.*\)/\1/g' | tr "\n" " " > /tmp/hosts_127_0_0_1
sed '/^127\.0\.0\.1/d' /etc/hosts > /tmp/hosts_sans_127_0_0_1
cat /tmp/hosts_sans_127_0_0_1 | sudo tee /etc/hosts > /dev/null
echo -n "127.0.0.1 localhost " | sudo tee -a /etc/hosts > /dev/null
cat /tmp/hosts_127_0_0_1 | sudo tee -a /etc/hosts > /dev/null
# apply :home_paths
for path_entry in $HOME/.local/bin $HOME/bin ; do
  if [[ ${PATH%%:*} != $path_entry ]] ; then
    export PATH="$path_entry:$PATH"
  fi
done

if [ ! $(uname|grep Darwin) ]; then echo update_initramfs=no | sudo tee -a /etc/initramfs-tools/update-initramfs.conf > /dev/null; fi

if [[ "$(sw_vers -productVersion 2>/dev/null | cut -d . -f 2)" -lt 12 ]]; then
  mkdir -p $HOME/.ssh
  chmod 0700 $HOME/.ssh
  touch $HOME/.ssh/config
  echo -e "Host *
    UseRoaming no
  " | cat - $HOME/.ssh/config > $HOME/.ssh/config.tmp && mv $HOME/.ssh/config.tmp $HOME/.ssh/config
fi

function travis_debug() {
echo -e "\033[31;1mThe debug environment is not available. Please contact support.\033[0m"
false
}

if [[ $(command -v sw_vers) ]]; then
  travis_cmd rvm\ use --echo
fi

if [[ -L /usr/lib/jvm/java-8-oracle-amd64 ]]; then
  echo -e "Removing symlink /usr/lib/jvm/java-8-oracle-amd64"
  travis_cmd sudo\ rm\ -f\ /usr/lib/jvm/java-8-oracle-amd64 --echo
  if [[ -f $HOME/.jdk_switcher_rc ]]; then
    echo -e "Reload jdk_switcher"
    travis_cmd source\ \$HOME/.jdk_switcher_rc --echo
  fi
  if [[ -f /opt/jdk_switcher/jdk_switcher.sh ]]; then
    echo -e "Reload jdk_switcher"
    travis_cmd source\ /opt/jdk_switcher/jdk_switcher.sh --echo
  fi
fi

if [[ $(uname -m) != ppc64le && $(command -v lsb_release) && $(lsb_release -cs) != precise ]]; then
  travis_cmd sudo\ dpkg\ --add-architecture\ i386
fi

:
}

EOFUNC_CONFIGURE
cat <<'EOFUNC_CHECKOUT' >>$HOME/.travis/job_stages
function travis_run_checkout() {
export GIT_ASKPASS=echo

travis_fold start git.checkout
  if [[ ! -d HomeHero/harvey/.git ]]; then
    travis_cmd git\ clone\ --depth\=3\ -branch\=\'\dusk'\ https://github.com/HomeHero/harvey.git\ HomeHero/harvey --assert --echo --retry --timing
    if [[ $? -ne 0 ]]; then
      echo -e "\033[31;1mFailed to clone from GitHub.\033[0m"
      echo -e "Checking GitHub status (https://status.github.com/api/last-message.json):"
      curl -sL https://status.github.com/api/last-message.json | jq -r .[]
    fi
  else
    travis_cmd git\ -C\ HomeHero/harvey\ fetch\ origin --assert --echo --retry --timing
    travis_cmd git\ -C\ HomeHero/harvey\ reset\ --hard --assert --echo
  fi
  rm -f $HOME/.netrc
  travis_cmd cd\ HomeHero/harvey --echo
  travis_cmd git\ checkout\ -qf\  --assert --echo
travis_fold end git.checkout

if [[ -f .gitmodules ]]; then
  travis_fold start git.submodule
    echo Host\ github.com'
    '\	StrictHostKeyChecking\ no'
    ' >> ~/.ssh/config
    travis_cmd git\ submodule\ update\ --init\ --recursive --assert --echo --retry --timing
  travis_fold end git.submodule
fi

rm -f ~/.ssh/source_rsa
:
}

EOFUNC_CHECKOUT
cat <<'EOFUNC_PREPARE' >>$HOME/.travis/job_stages
function travis_run_prepare() {
export PS4=+
:
}

EOFUNC_PREPARE
cat <<'EOFUNC_DISABLE_SUDO' >>$HOME/.travis/job_stages
function travis_run_disable_sudo() {
:
}

EOFUNC_DISABLE_SUDO
cat <<'EOFUNC_EXPORT' >>$HOME/.travis/job_stages
function travis_run_export() {
export TRAVIS=true
export CI=true
export CONTINUOUS_INTEGRATION=true
export PAGER=cat
export HAS_JOSH_K_SEAL_OF_APPROVAL=true
export TRAVIS_ALLOW_FAILURE=''
export TRAVIS_EVENT_TYPE=''
export TRAVIS_PULL_REQUEST=false
export TRAVIS_SECURE_ENV_VARS=false
export TRAVIS_BUILD_ID=''
export TRAVIS_BUILD_NUMBER=''
export TRAVIS_BUILD_DIR=$HOME/build/HomeHero/harvey
export TRAVIS_JOB_ID=''
export TRAVIS_JOB_NUMBER=''
export TRAVIS_BRANCH=''
export TRAVIS_COMMIT=''
export TRAVIS_COMMIT_MESSAGE=$(git log --format=%B -n 1)
export TRAVIS_COMMIT_RANGE=''
export TRAVIS_REPO_SLUG=HomeHero/harvey
export TRAVIS_OS_NAME=''
export TRAVIS_LANGUAGE=php
export TRAVIS_TAG=''
export TRAVIS_SUDO=true
export TRAVIS_PULL_REQUEST_BRANCH=''
export TRAVIS_PULL_REQUEST_SHA=''
export TRAVIS_PULL_REQUEST_SLUG=''
export TRAVIS_PHP_VERSION=["7.1"]
:
}

EOFUNC_EXPORT
cat <<'EOFUNC_SETUP' >>$HOME/.travis/job_stages
function travis_run_setup() {
travis_cmd phpenv\ global\ \[\"7.1\"\]\ 2\>/dev/null --echo --timing

if [[ $? -ne 0 ]]; then
  echo -e "\033[33;1m["7.1"] is not pre-installed; installing\033[0m"
  if [[ $(uname) = 'Linux' ]]; then
    travis_host_os=$(lsb_release -is | tr 'A-Z' 'a-z')
    travis_rel_version=$(lsb_release -rs)
  elif [[ $(uname) = 'Darwin' ]]; then
    travis_host_os=osx
    travis_rel=$(sw_vers -productVersion)
    travis_rel_version=${travis_rel%*.*}
  fi
  archive_url=https://s3.amazonaws.com/travis-php-archives/binaries/${travis_host_os}/${travis_rel_version}/$(uname -m)/php-["7.1"].tar.bz2
  echo -e "\033[33;1mDownloading archive: ${archive_url}\033[0m"
  travis_cmd curl\ -s\ -o\ archive.tar.bz2\ \$archive_url\ \&\&\ tar\ xjf\ archive.tar.bz2\ --directory\ / --echo --timing
  travis_cmd rm\ -f\ archive.tar.bz2 --assert --timing
fi

travis_cmd phpenv\ global\ \[\"7.1\"\] --assert --echo --timing
phpenv rehash
travis_cmd composer\ self-update\ 1.0.0 --echo --timing
travis_cmd composer\ self-update --echo --timing
:
}

EOFUNC_SETUP
cat <<'EOFUNC_SETUP_CASHER' >>$HOME/.travis/job_stages
function travis_run_setup_casher() {

travis_fold start cache.1
  echo -e "Setting up build cache"
  rvm use $(rvm current >&/dev/null) >&/dev/null
  travis_cmd export\ CASHER_DIR\=\$HOME/.casher --echo
  mkdir -p $CASHER_DIR/bin
  travis_cmd curl\ -sf\ \ -o\ \$CASHER_DIR/bin/casher\ https:///files/casher --echo --display Installing\ caching\ utilities --retry --timing
  if [[ $? -ne 0 ]]; then
    travis_cmd curl\ -sf\ \ -o\ \$CASHER_DIR/bin/casher\ https://raw.githubusercontent.com/travis-ci/casher/production/bin/casher --echo --display Installing\ caching\ utilities\ from\ the\ Travis\ CI\ server\ \(https:///files/casher\)\ failed,\ failing\ over\ to\ using\ GitHub\ \(https://raw.githubusercontent.com/travis-ci/casher/production/bin/casher\) --retry --timing
  fi
  if [[ $? -ne 0 ]]; then
    echo -e "\033[33;1mFailed to fetch casher from GitHub, disabling cache.\033[0m"
  fi
  if [[ -f $CASHER_DIR/bin/casher ]]; then
    chmod +x $CASHER_DIR/bin/casher
  fi
  if [[ $- = *e* ]]; then
    ERREXIT_SET=true
  fi
  set +e
  if [[ -f $CASHER_DIR/bin/casher ]]; then
    travis_cmd type\ rvm\ \&\>/dev/null\ \|\|\ source\ \~/.rvm/scripts/rvm --timing
    travis_cmd rvm\ \$\(travis_internal_ruby\)\ --fuzzy\ do\ \$CASHER_DIR/bin/casher\ fetch\ https://s3.amazonaws.com/cache_bucket/1234567890//cache-trusty-e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855--php-7.1.tgz\\\?X-Amz-Algorithm\\\=AWS4-HMAC-SHA256\\\&X-Amz-Credential\\\=abcdef0123456789\\\%2F20170509\\\%2Fus-east-1\\\%2Fs3\\\%2Faws4_request\\\&X-Amz-Date\\\=20170509T043221Z\\\&X-Amz-Expires\\\=60\\\&X-Amz-Signature\\\=49a46ab27c047b21a50be407078e7d366ccf474afcdebc49919af0606d663627\\\&X-Amz-SignedHeaders\\\=host\ https://s3.amazonaws.com/cache_bucket/1234567890//cache--php-7.1.tgz\\\?X-Amz-Algorithm\\\=AWS4-HMAC-SHA256\\\&X-Amz-Credential\\\=abcdef0123456789\\\%2F20170509\\\%2Fus-east-1\\\%2Fs3\\\%2Faws4_request\\\&X-Amz-Date\\\=20170509T043221Z\\\&X-Amz-Expires\\\=60\\\&X-Amz-Signature\\\=de99d07a3a61e023ba10d4dc9716721eb1575aeb5dd77a1f3cce85500f8e7e6c\\\&X-Amz-SignedHeaders\\\=host\ https://s3.amazonaws.com/cache_bucket/1234567890/cache-trusty-e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855--php-7.1.tgz\\\?X-Amz-Algorithm\\\=AWS4-HMAC-SHA256\\\&X-Amz-Credential\\\=abcdef0123456789\\\%2F20170509\\\%2Fus-east-1\\\%2Fs3\\\%2Faws4_request\\\&X-Amz-Date\\\=20170509T043221Z\\\&X-Amz-Expires\\\=60\\\&X-Amz-Signature\\\=cb6508487b02ca32ee4c42021297b50b4aef0cba945839c11965575219138acf\\\&X-Amz-SignedHeaders\\\=host\ https://s3.amazonaws.com/cache_bucket/1234567890/cache--php-7.1.tgz\\\?X-Amz-Algorithm\\\=AWS4-HMAC-SHA256\\\&X-Amz-Credential\\\=abcdef0123456789\\\%2F20170509\\\%2Fus-east-1\\\%2Fs3\\\%2Faws4_request\\\&X-Amz-Date\\\=20170509T043221Z\\\&X-Amz-Expires\\\=60\\\&X-Amz-Signature\\\=6de077af224b13dac49e2624c1748fda75745618a93e12209e7c720f42218d88\\\&X-Amz-SignedHeaders\\\=host --timing
  fi
  if [[ -n $ERREXIT_SET ]]; then
    set -e
  fi
  if [[ $- = *e* ]]; then
    ERREXIT_SET=true
  fi
  set +e
  if [[ -f $CASHER_DIR/bin/casher ]]; then
    travis_cmd type\ rvm\ \&\>/dev/null\ \|\|\ source\ \~/.rvm/scripts/rvm --timing
    travis_cmd rvm\ \$\(travis_internal_ruby\)\ --fuzzy\ do\ \$CASHER_DIR/bin/casher\ add\ \$HOME/.composer/cache\ \$HOME/node_modules --timing
  fi
  if [[ -n $ERREXIT_SET ]]; then
    set -e
  fi
travis_fold end cache.1

:
}

EOFUNC_SETUP_CASHER
cat <<'EOFUNC_SETUP_CACHE' >>$HOME/.travis/job_stages
function travis_run_setup_cache() {
:
}

EOFUNC_SETUP_CACHE
cat <<'EOFUNC_ANNOUNCE' >>$HOME/.travis/job_stages
function travis_run_announce() {
travis_cmd php\ --version --echo
travis_cmd composer\ --version --echo
echo -e "${ANSI_RESET}"
:
}

EOFUNC_ANNOUNCE
cat <<'EOFUNC_DEBUG' >>$HOME/.travis/job_stages
function travis_run_debug() {
:
}

EOFUNC_DEBUG
cat <<'EOFUNC_BEFORE_INSTALL' >>$HOME/.travis/job_stages
function travis_run_before_install() {

travis_fold start before_install.1
  travis_cmd nvm\ install\ 7.4.0 --assert --echo --timing
travis_fold end before_install.1

travis_fold start before_install.2
  travis_cmd nvm\ use\ 7.4.0 --assert --echo --timing
travis_fold end before_install.2

travis_fold start before_install.3
  travis_cmd npm\ cache\ clean --assert --echo --timing
travis_fold end before_install.3

travis_fold start before_install.4
  travis_cmd npm\ install --assert --echo --timing
travis_fold end before_install.4

travis_fold start before_install.5
  travis_cmd touch\ /home/travis/build/HomeHero/harvey/harvey_dusk.sqlite --assert --echo --timing
travis_fold end before_install.5

travis_fold start before_install.6
  travis_cmd cp\ .env.dusk.travis\ .env --assert --echo --timing
travis_fold end before_install.6

:
}

EOFUNC_BEFORE_INSTALL
cat <<'EOFUNC_INSTALL' >>$HOME/.travis/job_stages
function travis_run_install() {
:
}

EOFUNC_INSTALL
cat <<'EOFUNC_BEFORE_SCRIPT' >>$HOME/.travis/job_stages
function travis_run_before_script() {

travis_fold start before_script.1
  travis_cmd export\ DB_CONNECTION\=sqlite --assert --echo --timing
travis_fold end before_script.1

travis_fold start before_script.2
  travis_cmd export\ DB_DATABASE\=/home/travis/build/HomeHero/harvey/harvey_dusk.sqlite --assert --echo --timing
travis_fold end before_script.2

travis_fold start before_script.3
  travis_cmd phpenv\ config-rm\ xdebug.ini --assert --echo --timing
travis_fold end before_script.3

travis_fold start before_script.4
  travis_cmd composer\ self-update --assert --echo --timing
travis_fold end before_script.4

travis_fold start before_script.5
  travis_cmd composer\ install\ --no-interaction --assert --echo --timing
travis_fold end before_script.5

travis_fold start before_script.6
  travis_cmd export\ DISPLAY\=:99.0 --assert --echo --timing
travis_fold end before_script.6

travis_fold start before_script.7
  travis_cmd /usr/bin/Xvfb\ :99\ -ac\ -screen\ 0\ 2560x1440x24\ \& --assert --echo --timing
travis_fold end before_script.7

travis_fold start before_script.8
  travis_cmd ./vendor/laravel/dusk/bin/chromedriver-linux\ --silent\ \& --assert --echo --timing
travis_fold end before_script.8

:
}

EOFUNC_BEFORE_SCRIPT
cat <<'EOFUNC_SCRIPT' >>$HOME/.travis/job_stages
function travis_run_script() {
travis_cmd php\ artisan\ migrate\ --seed --echo --timing
travis_result $?
travis_cmd php\ artisan\ serve\ --host\=localhost\ --quiet\ \>\ /dev/null\ 2\>\&1\ \& --echo --timing
travis_result $?
travis_cmd php\ artisan\ dusk --echo --timing
travis_result $?
travis_cmd sudo\ killall\ -s\ SIGKILL\ Xvfb\ chromedriver-linux\ php --echo --timing
travis_result $?
travis_cmd vendor/bin/phpunit --echo --timing
travis_result $?
:
}

EOFUNC_SCRIPT
cat <<'EOFUNC_BEFORE_CACHE' >>$HOME/.travis/job_stages
function travis_run_before_cache() {
:
}

EOFUNC_BEFORE_CACHE
cat <<'EOFUNC_CACHE' >>$HOME/.travis/job_stages
function travis_run_cache() {

travis_fold start cache.2
  echo -e "store build cache"
  if [[ $- = *e* ]]; then
    ERREXIT_SET=true
  fi
  set +e
  if [[ -n $ERREXIT_SET ]]; then
    set -e
  fi
  if [[ $- = *e* ]]; then
    ERREXIT_SET=true
  fi
  set +e
  if [[ -f $CASHER_DIR/bin/casher ]]; then
    travis_cmd type\ rvm\ \&\>/dev/null\ \|\|\ source\ \~/.rvm/scripts/rvm --timing
    travis_cmd rvm\ \$\(travis_internal_ruby\)\ --fuzzy\ do\ \$CASHER_DIR/bin/casher\ push\ https://s3.amazonaws.com/cache_bucket/1234567890//cache-trusty-e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855--php-7.1.tgz\\\?X-Amz-Algorithm\\\=AWS4-HMAC-SHA256\\\&X-Amz-Credential\\\=abcdef0123456789\\\%2F20170509\\\%2Fus-east-1\\\%2Fs3\\\%2Faws4_request\\\&X-Amz-Date\\\=20170509T043221Z\\\&X-Amz-Expires\\\=60\\\&X-Amz-Signature\\\=955374ec979f99428abfedc01b6a797caa3ffa329a6d30a22e3f38dfe3a0ecb3\\\&X-Amz-SignedHeaders\\\=host --timing
  fi
  if [[ -n $ERREXIT_SET ]]; then
    set -e
  fi
travis_fold end cache.2

:
}

EOFUNC_CACHE
cat <<'EOFUNC_RESET_STATE' >>$HOME/.travis/job_stages
function travis_run_reset_state() {
:
}

EOFUNC_RESET_STATE
cat <<'EOFUNC_AFTER_SUCCESS' >>$HOME/.travis/job_stages
function travis_run_after_success() {
:
}

EOFUNC_AFTER_SUCCESS
cat <<'EOFUNC_AFTER_FAILURE' >>$HOME/.travis/job_stages
function travis_run_after_failure() {
:
}

EOFUNC_AFTER_FAILURE
cat <<'EOFUNC_AFTER_SCRIPT' >>$HOME/.travis/job_stages
function travis_run_after_script() {
:
}

EOFUNC_AFTER_SCRIPT
cat <<'EOFUNC_FINISH' >>$HOME/.travis/job_stages
function travis_run_finish() {
:
}

EOFUNC_FINISH
source $HOME/.travis/job_stages
travis_run_configure
travis_run_checkout
travis_run_prepare
travis_run_disable_sudo
travis_run_export
travis_run_setup
travis_run_setup_casher
travis_run_setup_cache
travis_run_announce
travis_run_debug
travis_run_before_install
travis_run_install
travis_run_before_script
travis_run_script
travis_run_before_cache
travis_run_cache
travis_run_after_success
travis_run_after_failure
travis_run_after_script
travis_run_finish
echo -e "\nDone. Your build exited with $TRAVIS_TEST_RESULT."

travis_terminate $TRAVIS_TEST_RESULT
