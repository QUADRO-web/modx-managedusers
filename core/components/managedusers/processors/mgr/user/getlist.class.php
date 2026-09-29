<?php
/**
 * Get a list of all users which belong exclusively to the managed user group
 *
 * @package managedusers
 * @subpackage processors
 */
require_once __DIR__ . '/_base.php';

class ManagedUsersUserGetListProcessor extends modObjectGetListProcessor
{
    use ManagedUsersProcessorTrait;

    public $classKey = 'modUser';
    public $defaultSortField = 'username';
    public $checkListPermission = false;

    /** @var array Sortable fields (whitelist) */
    protected $sortFields = array(
        'id' => 'modUser.id',
        'username' => 'modUser.username',
        'active' => 'modUser.active',
        'fullname' => 'Profile.fullname',
        'email' => 'Profile.email',
    );

    public function initialize()
    {
        $initialized = $this->initializeManagedUsers();
        if ($initialized !== true) {
            return $initialized;
        }
        return parent::initialize();
    }

    public function beforeQuery()
    {
        $sort = $this->getProperty('sort');
        if (!isset($this->sortFields[$sort])) {
            $sort = $this->defaultSortField;
        }
        $this->setProperty('sort', $this->sortFields[$sort]);
        $this->setProperty('dir', strtoupper($this->getProperty('dir')) === 'DESC' ? 'DESC' : 'ASC');
        return true;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->innerJoin('modUserProfile', 'Profile');
        $c->where(array('modUser.sudo' => false));
        $c->where($this->managedusers->getExclusiveMemberCondition($this->group));

        $query = trim((string) $this->getProperty('query'));
        if ($query !== '') {
            $c->where(array(
                'modUser.username:LIKE' => '%' . $query . '%',
                'OR:Profile.fullname:LIKE' => '%' . $query . '%',
                'OR:Profile.email:LIKE' => '%' . $query . '%',
            ));
        }
        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns('modUser', 'modUser', '', array('id', 'username', 'active')));
        $c->select($this->modx->getSelectColumns('modUserProfile', 'Profile', '', array('fullname', 'email')));
        return $c;
    }

    public function prepareRow(xPDOObject $object)
    {
        return array(
            'id' => (int) $object->get('id'),
            'username' => $object->get('username'),
            'fullname' => $object->get('fullname'),
            'email' => $object->get('email'),
            'active' => (bool) $object->get('active'),
        );
    }
}

return 'ManagedUsersUserGetListProcessor';
