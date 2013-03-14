Databases
=========

When you're developing an app, it is 100% sure you'll need to persist and read information to and from a database. Fortunately, PPI makes it simple to work with databases with our powerful DataSources component, which makes use of Doctrine DBAL layer, a library whose sole goal is to give you robust tools to make this easy. In this chapter, you'll learn the basic philosophy behind doctrine and see how easy is to use the DataSource component to work with databases.

.. citations::

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

.. citations::

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

As you can see, the class is pretty explanatory by itself, you have