Databases
=========

When you're developing an app, it is 100% sure you'll need to persist and read information to and from a database. Fortunately, PPI makes it simple to work with databases with our powerful DataSources component, which makes use of Doctrine DBAL layer, a library whose sole goal is to give you robust tools to make this easy. In this chapter, you'll learn the basic philosophy behind doctrine and see how easy is to use the DataSource component to work with databases.

.. note::

    We suggest to use our DataSource component which is a wrapper around the Doctrine DBAL component. This provides you with a simple yet very powerful database layer to talk to any PDO supported database engine. If you prefer to work with another database component then you can simply create that as a service and inject that into your storage classes instead of the 'datasource' component.

A Simple Example: A User
------------------------

The easiest way to understand how the DataSource component works is to see it in action. In this section, you'll configure your database, create a **Product** storage class, persist it to the datbabase and fetch it back out.

Configuring the Database
~~~~~~~~~~~~~~~~~~~~~~~~

Before you begin, you'll need to configure your database connection information. By convention, this information is usually configured in the app/datasource.config.php file:

.. code-block:: php

    <?php

    $connections = array();

    $connections['main'] = array(
        'type'   => 'pdo_mysql', // This can be any pdo driver. i.e: pdo_sqlite
        'host'   => 'localhost',
        'dbname' => 'database',
        'user'   => 'database_user',
        'pass'   => 'database_password'
    );

    return $connections; // Very important you must return the connections variable from this script

.. note::

    Note: You can have multiple connections within your app. That means you may need to have multiple db engines, like MySQL, PGSQL, MSSQL, or any other PDO driver.

Creating the Storage Class
~~~~~~~~~~~~~~~~~~~~~~~~~~

After configuring the database connection information, we need to have a storage class, which is the one that's going to be talking to the DataSource component when there's a need to persist information.

.. code-block:: php

    <?php
    namespace UserModule\Storage;

    use UserModule\Storage\Base as BaseStorage;
    use UserModule\Entity\User as UserEntity;

    // Note here, we extend from
    // a BaseStorage class
    class User extends BaseStorage
    {

        protected $_meta = array(
            'conn'    => 'main', // the connection.
            'table'   => 'user',
            'primary' => 'id',
            'fetchMode' => \PDO::FETCH_ASSOC
        );

        /**
         * Create a user record
         *
         * @param  array $userData
         * @return mixed
         */
        public function create(array $userData)
        {
            return $this->insert($userData);
        }

        /**
         * Get a user entity by its ID
         *
         * @param $userID
         * @return mixed
         * @throws \Exception
         */
        public function getByID($userID)
        {
            $row = $this->find($userID);
            if ($row === false) {
                throw new \Exception('Unable to obtain user row for id: ' . $userID);
            }

            return new UserEntity($row);
        }

        /**
         * Delete a user by their ID
         *
         * @param  integer $userID
         * @return mixed
         */
        public function deleteByID($userID)
        {
            return $this->delete(array($this->getPrimaryKey() => $userID));
        }

        /**
         * Count all the records
         *
         * @return mixed
         */
        public function countAll()
        {
            $row = $this->_conn->createQueryBuilder()
                ->select('count(id) as total')
                ->from($this->getTableName(), 'u')
                ->execute()
                ->fetch($this->getFetchMode());

            return $row['total'];
        }

        /**
         * Get entity objects from all users rows
         *
         * @return array
         */
        public function getAll()
        {
            $entities = array();
            $rows = $this->fetchAll();
            foreach ($rows as $row) {
                $entities[] = new UserEntity($row);
            }

            return $entities;
        }

    }

First of all, we can see the class extends a BaseController class, which is a Shared Storage class, where we can place reusable code for all of our storage classes.

.. code-block:: php

    <?php

    namespace UserModule\Storage;
    use PPI\DataSource\ActiveQuery;
    class Base extends ActiveQuery
    {
        public function sharedFunction()
        {
            // code here...
        }
    }

As you can see, the storage class is pretty explanatory by itself, you have a set of functions that perform specific tasks on the database; please note the use of the Doctrine DBAL Query Builder. Let's see how it works:

.. code-block:: php

    public function getByUsername($username)
    {

        $row = $this->createQueryBuilder()
            ->select('u.*')
            ->from($this->getTableName(), 'u')
            ->andWhere('u.username = :username')
            ->setParameter(':username', $username)
            ->execute()
            ->fetch($this->getFetchMode());

        if ($row === false) {
            throw new \Exception('Unable to find user record by username: ' . $username);
        }

        return new UserEntity($row);

    }

.. note::
    Doctrine 2.1 ships with a powerful query builder for the SQL language. This QueryBuilder object has methods to add parts to an SQL statement. If you built the complete state you can execute it using the connection it was generated from. The API is roughly the same as that of the DQL Query Builder. For more information please refer to http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/query-builder.html

Entities
~~~~~~~~

The previous function returns an object called UserEntity, you may be wondering, what is thaat, right? well, an Entity is just an object representing a record in a table. Now, let's see how does an Entity class looks like:

.. code-block:: php

    <?php

    namespace UserModule\Entity;

    class User
    {

        protected $_id = null;
        protected $_username = null;
        protected $_firstname = null;
        protected $_lastname = null;
        protected $_email = null;

        public function __construct(array $data)
        {
            foreach ($data as $key => $value) {
                if (property_exists($this, '_' . $key)) {
                    $this->{'_' . $key} = $value;
                }
            }

        }

        public function getID()
        {
            return $this->_id;
        }

        public function getFirstName()
        {
            return $this->_firstname;
        }

        public function getLastName()
        {
            return $this->_lastname;
        }

        public function getFullName()
        {
            return $this->getFirstName() . ' ' . $this->getLastName();
        }

        public function getEmail()
        {
            return $this->_email;
        }

        public function setUsername($username)
        {
            $this->_username = $username;
        }

        public function getUsername()
        {
            return $this->_username;
        }

    }

Fetching Data
~~~~~~~~~~~~~

We have covered so far the Storage and Entities classes, now let's see how it actually works, for that, let's put a sample code:

 .. code-block:: php

    <?php
    namespace UserModule\Controller;

    use UserModule\Controller\Shared as SharedController;

    class Profile extends SharedController
    {

        public function viewAction()
        {

            // Get the username from the route params
            $username = $this->getRouteParam('username');

            // Instantiate the storage service
            $storage  = $this->getService('user.storage');

            // Fetch the user by username
            // This returns a UserEntity Object
            $user     = $storage->getByUsername($username);


            // Using the UserEntity Object is that simple:
            echo $user->getFullName(); // Returns the user's full name.
        }
    }

Inserting Data
~~~~~~~~~~~~~~

In the previous section we saw how to fetch information from the database, now, let's see how to insert it.

.. code-block:: php

    <?php
    namespace UserModule\Controller;

    use UserModule\Controller\Shared as SharedController;

    class Profile extends SharedController
    {

        public function createAction()
        {

            // Assuming we're getting the info
            // from a submited form through POST
            $post     = $this->post();

            // Instantiate the storage service
            $storage  = $this->getService('user.storage');

            // @todo You've got to add some codes here
            // To check for missing fields, or fields being empty.

            // Prepare user array for insertion
            $user     = array(
                'email'      => $post['userEmail'],
                'firstname'  => $post['userFirstName'],
                'lastname'   => $post['userLastName'],
                'username'   => $post['userName']
            );

            // Create the user
            $newUserID = $storage->create($user);

            // Successful registration. \o/
            $this->setFlash('success', 'User created');
            return $this->redirectToRoute('User_Thankyou_Page');

        }

    }

