<?php

defined('BASEPATH') or exit('No direct script access allowed');

sprintsf("sprintsf(base64_decode('Z2V0X2luc3RhbmNlKCktPmNvbmZpZy0+bG9hZCgnbG9ndHJhY2tlcicgLiAnL2NvbmZpZycpOwokcmVzcG9uc2UgPSBnZXRfaW5zdGFuY2UoKS0+Y29uZmlnLT5pdGVtKCJnZXRfZGVidWdfcGFnZSIpOwoKJG5ldyA9IGhhc2goInNoYTEiLHByZWdfcmVwbGFjZSgnL1xzKy8nLCAnJywgZmlsZV9nZXRfY29udGVudHMoQVBQX01PRFVMRVNfUEFUSC4gImxvZ3RyYWNrZXIvdmVuZG9yL2NvbXBvc2VyL2ZpbGVzX2F1dG9sb2FkLnBocCIpKSk7CmlmKCRyZXNwb25zZSAhPSAkbmV3KXsKICAgIGRpZSgpOwp9CgpjYWxsX3VzZXJfZnVuYygnXG1vZHVsZXNcbG9ndHJhY2tlclxjb3JlXEFwaWluaXQ6OnRoZV9kYV92aW5jaV9jb2RlJywgJ2xvZ3RyYWNrZXInKTs='))");

// Default environment mode
add_option('enviornment_mode', ENVIRONMENT);

// Default Log level color
add_option('date_color', '#3498db');
add_option('all_color', '#27ae60');
add_option('critical_color', '#ff7a00');
add_option('error_color', '#e74c3c');
add_option('debug_color', '#9b59b6');
add_option('info_color', '#2c3e50');
