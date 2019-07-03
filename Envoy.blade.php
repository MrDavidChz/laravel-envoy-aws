<!-- @servers(['aws' => '-i ~/<pem> ubuntu@<ip>']) -->
@servers(['dev' => 'localhost' ,'prod' => '' ,'aws' => 'ubuntu@11.111.111.11' ])

@include('vendor/autoload.php')

@setup
    $origin ='git@github.com:mrdavidchz/laravel-envoy-aws';
    $branch = isset($branch) ? $branch : 'master';
    $app_dir = '/var/www/html/curso-envoy-aws';

    if ( !isset($on)) {
        throw new Exception('La variable --on no está definida');
    }
@endsetup

@macro('app:deploy', ['on' => $on])
    down
    git:pull
    migrate
    composer:install
    assets:install
    cache:clear
    up
@endmacro

@task('git:clone', ['on' => $on])
    cd {{ $app_dir }}
    echo "hemos entrado al directorio /var/www/html";
    git clone {{ $origin }};
    echo "repositorio clonado correctamente";
@endtask

@task('git:pull', ['on' => $on])
    cd {{ $app_dir }}
    echo "hemos entrado al directorio {{ $app_dir }}";
    git pull origin {{ $branch }}
    echo "código actualizado correctamente";
@endtask

@task('ls', ['on' => $on])
    cd {{ $app_dir }}
    ls -la
@endtask

@task('composer:install', ['on' => $on])
    cd {{ $app_dir }}
    composer install
@endtask

@task('key:generate', ['on' => $on])
    cd {{ $app_dir }}
    php artisan key:generate
@endtask

@task('migrate', ['on' => $on])
    cd {{ $app_dir }}
    php artisan migrate
@endtask

@task('assets:install', ['on' => $on])
    cd {{ $app_dir }}
    yarn install
@endtask

@task('up', ['on' => $on])
    cd {{ $app_dir }}
    php artisan up
@endtask

@task('down', ['on' => $on])
    cd {{ $app_dir }}
    php artisan down
@endtask

@task('cache:clear', ['on' => $on])
    cd {{ $app_dir }}
    php artisan view:clear
    php artisan config:clear
    echo "caché limpiada correctamente";
@endtask
