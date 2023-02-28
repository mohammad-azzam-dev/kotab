<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes(true);

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
	// General
	$routes->get('/', 'Home::index');

	// Auth
	$routes->group('auth', function($routes)
	{
		$routes->add('login', 'AuthController::login');
		$routes->add('register', 'AuthController::register');
		$routes->add('logout', 'AuthController::logout');
		$routes->add('change-password', 'AuthController::change_password');
	});

	// API
	$routes->group('api', ['filter' => 'tokenChecker'], function($routes)
	{
		// Auth
		$routes->group('auth', function($routes)
		{
			$routes->add('login', 'API/API_AuthController::login');
			$routes->add('change-password', 'API/API_AuthController::change_password');
		});

		// Classes
		$routes->group('classes', function($routes)
		{
			$routes->get('list/(:segment)', 'API\Academic\API_ClassesController::list/$1');
			$routes->post('remove-student/(:num)/(:num)', 'API\Academic\API_ClassesController::remove_student_request/$1/$2');
			$routes->get('places-list', 'API\Academic\API_ClassesController::get_places_list');
		});

		// Reports
		$routes->group('reports', function($routes)
		{
			$routes->get('new/(:num)/json', 'Academic\ReportsController::new/$1/json');
			$routes->post('create/(:num)', 'API\Academic\API_ReportsController::create/$1');
			$routes->get('show/(:num)', 'API\Academic\API_ReportsController::show/$1');
			$routes->get('show-summary/(:num)', 'API\Academic\API_ReportsController::show_summary/$1');
		});
	});

	// Dashboard
	$routes->group('dashboard', function($routes)
	{
		$routes->get('/', 'DashboardController::index');

		// Users
		$routes->group('users', function($routes)
		{
			$routes->get('', 'UsersController::index');
			$routes->post('create', 'UsersController::create');
			$routes->get('show/(:num)', 'UsersController::show/$1');
			$routes->get('edit/(:num)', 'UsersController::edit/$1');
			$routes->post('update/(:num)', 'UsersController::update/$1');
			$routes->add('delete/(:num)', 'UsersController::delete/$1');
			$routes->get('dataTable', 'UsersController::dataTable');

			$routes->get('get-user-classes/(:num)', 'UsersController::get_user_classes/$1');
			$routes->get('get-parents-data', 'UsersController::get_parents_data');
			$routes->get('getUserParents/(:num)', 'UsersController::getUserParents/$1');
			$routes->post('assign-parent/(:num)', 'UsersController::assign_parent/$1');
			$routes->get('get-user-achievements/(:num)', 'UsersController::get_user_achievements/$1');
			$routes->post('store-completed-achievements/(:num)', 'UsersController::store_completed_achievements/$1');
		});

		// Classes
		$routes->group('classes', function($routes)
		{

		});
	});

	// CLasses
	$routes->get('/dashboard/classes/all-classes', 'ClassesController::classesList/all');
	$routes->get('/dashboard/classes/my-classes', 'ClassesController::classesList/myClasses');
	$routes->get('/dashboard/classes/instructor-classes', 'ClassesController::classesList/instructorClasses');
	$routes->get('/dashboard/classes/my-children-classes', 'ClassesController::classesList/myChildrenClasses');

	// Majors
	$routes->get('/dashboard/majors', 'MajorsController::index');

	// Courses
	$routes->get('/dashboard/courses', 'CoursesController::index');
	
	// Achevements
	$routes->get('/dashboard/achievements', 'Academic/AchievementsParentsController::index');

	// General
	$routes->get('/under_maintenance', 'GeneralController::under_maintenance');



/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need to it be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

/**
 * Include Modules Routes Files
 */
if (file_exists(ROOTPATH.'modules')) {
	$modulesPath = ROOTPATH.'modules/';
	$modules = scandir($modulesPath);

	foreach ($modules as $module) {
		if ($module === '.' || $module === '..') continue;
		
		if (is_dir($modulesPath) . '/' . $module) {
			$routesPath = $modulesPath . $module . '/Config/Routes.php';
			if (file_exists($routesPath)) {
				require($routesPath);
			}
			else {
				continue;
			}
		}
	}
}
