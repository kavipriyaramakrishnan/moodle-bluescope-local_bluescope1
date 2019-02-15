<?php
/*
 * Bluescope
 *
 * Defining Capabilities
 * @package    : local_bluescope
 * @copyright  : 2018 Pukunui
 * @author    : Priya Ramakrishnan, Pukunui {@link http://pukunui.com}
 * @license   : http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$capabilities = array(
                    'local/bluescope:addusertype' => array(
                        'riskbitmask' => RISK_DATALOSS,
                        'captype'     => 'read',
                        'contextlevel'=> CONTEXT_SYSTEM,
                        'archetypes'  => array(
                            'manager'   => CAP_ALLOW
                            )
                    )
);
