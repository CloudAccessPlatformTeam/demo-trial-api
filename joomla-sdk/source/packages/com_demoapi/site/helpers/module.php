<?php
class DemoApiHelperModule
{
    /**
     * Get module params
     *
     * @param $module_id
     * @return JRegistry
     */
    static public function getParams($module_id)
    {
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);

        $query->select('params');
        $query->from('#__modules');
        $query->where($db->quoteName('id') . ' = ' . $db->quote($module_id));

        $db->setQuery($query);

        $row = $db->loadObject();
        $params = new JRegistry(json_decode($row->params, true));

        return $params;
    }
}