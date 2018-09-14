<?php

    namespace IdnoPlugins\LTI {

        use Idno\Common\Plugin;

        class Main extends Plugin {

            /**
             * @var \LTI_Data_Connector
             */
            public $db_connector = false;

            function registerPages() {

                \Idno\Core\site()->addPageHandler('admin/lti/?', 'IdnoPlugins\LTI\Pages\Admin');
                \Idno\Core\site()->addPageHandler('lti/callback/?', 'IdnoPlugins\LTI\Pages\Callback', true);
                \Idno\Core\site()->addPageHandler('lti/forwarder/?', 'IdnoPlugins\LTI\Pages\Forwarder', true);

                \Idno\Core\site()->template()->extendTemplate('admin/menu/items', 'lti/admin/menu');

            }

            function registerEventHooks() {

                if (empty(\Idno\Core\site()->config()->lti_installed) && \Idno\Core\site()->session()->isAdmin() && empty(\Idno\Core\site()->session()->installing_lti)) {
                    \Idno\Core\site()->session()->installing_lti = true;
                    if ($result = $this->installDatabaseSchema()) {
                        \Idno\Core\site()->session()->addMessage("LTI was installed.");
                        \Idno\Core\site()->config()->lti_installed = true;
                        \Idno\Core\site()->config()->save();
                    }
                    \Idno\Core\site()->session()->installing_lti = false;
                }

                \Idno\Core\site()->config()->unique_urls = true;

            }

            function installDatabaseSchema()
            {

                if (strtolower(\Idno\Core\site()->config()->database) == 'mysql') {
                    if ($schema = file_get_contents(dirname(__FILE__) . '/external/LTI_Tool_Provider/lti-tables-mysql.sql')) {
                        $dbh = \Idno\Core\site()->db()->getClient();
                        try {
                            if ($dbh->exec($schema)) {
                                return true;
                            }
                        } catch (\Exception $e) {
                            \Idno\Core\site()->logging()->log("Database error: " . json_encode($dbh->errorInfo()));
                        }
                    }
                }
                return false;

            }

            function loadLTI()
            {
                require_once(dirname(__FILE__) . '/external/LTI_Tool_Provider/LTI_Tool_Provider.php');
                $this->db_connector = \LTI_Data_Connector::getDataConnector('', \Idno\Core\site()->db()->getClient());
            }

        }

    }