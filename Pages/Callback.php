<?php

    namespace IdnoPlugins\LTI\Pages {

        use Idno\Common\Page;
        use IdnoPlugins\LTI\KnownToolProvider;

        class Callback extends Page {

            function get($params = []) {

                $lti = \Idno\Core\site()->plugins()->get('LTI'); /* @var \IdnoPlugins\LTI\Main $lti */
                $lti->loadLTI();

                $_SERVER['QUERY_STRING'] = '';  // Erasing this for compatibility
                unset($_REQUEST['/lti/callback/']);
                unset($_GET['/lti/callback/']);
                unset($_POST['/lti/callback/']);
                unset($_REQUEST['/lti/callback']);
                unset($_GET['/lti/callback']);
                unset($_POST['/lti/callback']);

                $tool = new KnownToolProvider($lti->db_connector);

                $tool->handle_request();

            }

            function post() {
                $this->get();
            }

        }

    }