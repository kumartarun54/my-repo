<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'user';

// $route[LOGIN_PAGE] = 'auth/login';
// $route['register'] = 'user/register';

$route['admin'] = 'Admin';
$route['admin/role-permission/create'] = 'Admin/CreatePermission';
$route['admin/module-user/list'] = 'Admin/ListModule';
$route['admin/module-user/create'] = 'Admin/CreateModule';
$route["admin/module-user/edit/(:any)"] = "Admin/EditModule/$1";
$route['admin/module-user/update/(:any)'] = "Admin/UpdateModule/$1";
$route['admin/module-user/delete/(:any)'] = "Admin/DeleteModule/$1";


// Route Of categroy
$route['admin/category/list'] = 'Admin/CategoryList';
$route['admin/category/create'] = 'Admin/CreateCategory';
$route["admin/category/edit/(:any)"] = "Admin/EditCategory/$1";
$route['admin/category/update/(:any)'] = "Admin/UpdateCategory/$1";
$route['admin/category/delete/(:any)'] = "Admin/DeleteCategory/$1";

//Route of Role
$route['admin/role/list'] = 'Admin/ListRole';
$route['admin/role/create'] = 'Admin/CreateRole';
$route["admin/role/edit/(:any)"] = "Admin/EditRole/$1";
$route['admin/role/update/(:any)'] = "Admin/UpdateRole/$1";
$route['admin/role/delete/(:any)'] = "Admin/DeleteRole/$1";

// Custom route of user
$route['user/course/(:any)'] = 'User/CourseUser/$1';
$route['user/usercourselist/(:any)'] = 'User/UserCourseList/$1';
$route['user/group/(:any)'] = 'User/Group/$1';
$route['user/usergrouplist/(:any)'] = 'User/UserGroupList/$1';
$route['user/branches/(:any)'] = 'User/Branches/$1';
$route['user/branchdatalist/(:any)'] = 'User/branchuserlist/$1';
$route['user/files/(:any)'] = 'User/UserFiles/$1';
$route['user/files/fileslist/(:any)'] = 'User/UserFilesList/$1';
$route['users/files/uploadimage'] = 'User/UserUploadImage';
$route['users/course_assgin'] = 'User/course_assgin';
$route['users/course_remove'] = 'User/course_remove';
$route['users/group_assgin'] = 'User/group_assgin';
$route['users/group_remove'] = 'User/group_remove';
$route['users/branch_assgin'] = 'User/branch_assgin';
$route['users/branch_remove'] = 'User/branch_remove';
// Custom route of course
$route['Course/user/(:any)'] = 'Courses/CourseUser/$1';
$route['Course/branches/(:any)'] = 'Courses/Branches/$1';
$route['Course/branchdatalist/(:any)'] = 'Courses/branchdatalist/$1';
$route['Course/group/(:any)'] = 'Courses/Group/$1';


// Custom route of Group
$route['group/users/(:any)'] = 'Group/GroupUser/$1';
$route['group/groupuserslist/(:any)'] = 'Group/GroupUsersList/$1';
$route['group/course/(:any)'] = 'Group/GroupCourse/$1';
$route['group/usercourselist/(:any)'] = 'Group/GroupCourseList/$1';
$route['group/files/(:any)'] = 'Group/GroupFiles/$1';
$route['group/fileslist/(:any)'] = 'Group/GroupFilesList/$1';
$route['group/file/uploadimage'] = 'Group/GroupUploadImage';

// Custom route of Branch
$route['branch/user/(:any)'] = 'Branches/BranchUser/$1';
$route['branch/course/(:any)'] = 'Branches/BranchCourses/$1';
$route['branch/files/(:any)'] = 'Branches/BranchFiles/$1';
$route['branch/files/fileslist/(:any)'] = 'Branches/FilesList/$1';
$route['branches/files/uploadimage'] = 'Branches/UploadImage';



// User Update 
$route["user/update/(:any)"] = "user/update/$1";

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;