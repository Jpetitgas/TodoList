Introduction

The authentication system is based on the Symfony security component.

In Symfony, the security system has two ways to manage:

Authentication that defines who the user is, managed by the firewall.
Authorization that determines whether a visitor or user has the right to access certain resources.

Authentication (see config / packages / security.yaml file)
1. Users
A user is represented by the User entity class, which implements UserInterface. This class is a Doctrine entity, users are stored in the database and are represented by their username attribute.

User passwords are encrypted in the database. The encoder is defined in the security.yaml file. In our case, it is the bcrypt encoder.

2. Firewall
The security.yaml file defines parameters such as:

The guard which is the authenticator used represented here by a UserAuthenticator class.

3. Access control
Attributes secure URLs.
IS_AUTHENTICATED_ANONYMOUSLY means all users, even those who are not authenticated.

4. Hierarchy of roles
we define a hierarchy of roles under the key role_hierarchy key of the security.yml file: the ROLE_ADMIN also has the ROLE_USER rights.


Authorization

1. Roles
Two roles are assigned to users, depending on the rights we wanted to assign them.
Thus, users can have a ROLE_USER or a ROLE_ADMIN.

2. Access control
These roles are used to secure the url templates under the access_control key in the security.yml file but also in the actions of the controller, or in a Twig view.
It is possible to use these attributes in a controller, via the method $ this-> isGranted ('IS_AUTHENTICATED_FULLY').
or
to use them from a Twig view {% if is_granted ('IS_AUTHENTICATED_FULLY')%}.

Note that once a user is authenticated, you can access them in a controller via the $ this-> getUser () method or in a Twig view via the global app.user.

In this application, Vote is used.
For more inforamtions:https://symfony.com/doc/4.4/components/security/authorization.html#voters

More About
https://symfony.com/doc/4.4/components/security.html
https://symfony.com/doc/4.4/components/security/authentication.html
https://symfony.com/doc/4.4/components/security/firewall.html