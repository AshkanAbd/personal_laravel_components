## How to use:
##### step 1: Copy components_config.php to Laravel config folder and rename it to components.php
##### step 2: Add `$this->load(components('COMPONENTS_FULL_DIR') . '/Commands');` to Laravel `app/Console/Kernel` class in `commands` method before `require base_path('routes/console.php');` line 
##### step 3: run `composer dump-autoload` to load helper and some functions 
##### step 4: You can some middlewares in `/Middleware` folder and bind them in `app/Http/Kernel` class.  

### Note 1: 
##### **_Some features are still in develop faze, using them will raise exception_** 

### Note 2: 
##### **_If your Laravel version is > 6, please undo step 2. It doesn't support on Laravel 7 and above_** 
