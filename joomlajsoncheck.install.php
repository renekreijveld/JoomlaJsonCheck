<?php
defined('_JEXEC') or die;
class plgSystemJoomlajsoncheckInstallerScript
{
	public $parameters;
	public $extension;
	public $extid;
	public $db;

	public function postflight($type, $parent)
	{
		echo "<p><strong>This plugin is based on the work of <a href=\"https://github.com/robwent/joomla-json-db-check\" target=\"_blank\">Robert Went</a>.</strong>&nbsp;";
		echo "Written by: <strong><a href=\"https://github.com/renekreijveld\" target=\"_blank\">René Kreijveld</a></strong>.</p>";
		$this->db = JFactory::getDbo();
		$config = JFactory::getConfig();
		$query = $this->db->getQuery(true)
			->select('TABLE_NAME,COLUMN_NAME')
			->from('INFORMATION_SCHEMA.COLUMNS')
			->where('COLUMN_NAME = \'params\' OR COLUMN_NAME = \'rules\'')
			->andWhere('TABLE_SCHEMA = \'' . $config->get('db') . '\'');
		$this->db->setQuery($query);
		$results = $this->db->loadObjectList();
		if ($results)
		{
			foreach ($results as $result)
			{
				echo "Checking table: {$result->TABLE_NAME}, column {$result->COLUMN_NAME}<br>";
				$query = $this->db->getQuery(true)
					->update($result->TABLE_NAME)
					->set($result->COLUMN_NAME . ' = "{}"')
					->where($result->COLUMN_NAME . ' = "" OR ' . $result->COLUMN_NAME . ' = \'{\"\"}\' OR ' . $result->COLUMN_NAME . ' = \'{\\\\\"\\\\\"}\' ');
				$this->db->setQuery($query);
				$results = $this->db->execute();
				$changes = $this->db->getAffectedRows();
				if ($changes != 0)
				{
					echo "<strong>" . $changes . " rows modified.</strong><br>";
				}
			}
		}
		// Uninstall myself from the extensions database table ...
		$this->db->setQuery($this->db->getQuery(true)
			->delete('#__extensions')
			->where($this->db->quoteName('type') . ' = ' . $this->db->quote('plugin'))
			->where($this->db->quoteName('folder') . ' = ' . $this->db->quote('system'))
			->where($this->db->quoteName('name') . ' = ' . $this->db->quote('joomlajsoncheck'))
		)->query();
		// ... and remove my files.
		JFolder::delete(JPATH_ROOT . '/plugins/system/joomlajsoncheck');
		echo "<br><p><strong>Al done! This plugin has now uninstalled itself.</strong></p>";
	}

}