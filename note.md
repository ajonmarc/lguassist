    
    //vite.config.ts
    server: {
        host: '0.0.0.0', // Allow external connections
        port: 5173,
        hmr: {
            host: 'localhost', // or your actual domain
        },
        cors: {
            origin: [
                'http://localhost',
                'http://localhost:8000',
                'http://lguassist.com',
                'https://lguassist.com'
            ],
            credentials: true
        }
    },

//
$table->enum('role', ['citizen','admin', 'lgu', ])->default('citizen');

 php artisan make:enum Enums/UserRole

 $ php artisan make:seeder AdminSeeder

 php artisan db:seed AdminSeeder

 npm install vue-toastification@next

 






