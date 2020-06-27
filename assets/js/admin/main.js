( function( $, window ) {
    "use strict";

    $( document ).ready( function() {

        /**
         * ACF 5 Installer
         */
        $( document ).ready( function() {

            var $acfInstaller = $( '#oxygen-acf5-pro-install-button' );

            if ( $acfInstaller.length ) {

                $acfInstaller.on( 'click', function( ev ) {
                    ev.preventDefault();

                    if ( $acfInstaller.hasClass( 'disabled' ) || $acfInstaller.hasClass( 'done' ) ) {
                        if ( $acfInstaller.hasClass( 'done' ) ) {
                            window.location.reload();
                        }
                        return;
                    }

                    $acfInstaller.addClass( 'disabled' ).append( '&hellip;' );

                    // Status variables
                    var acf4Deactivated = false,
                        acf5Activated = false;

                    // Disable ACF4 Plugin
                    $.post( window.location.pathname, {
                        oxygen_acf4_deactivate : 1
                    }, function( response ) {

                        if ( response == '1' ) {
                            acf4Deactivated = true;

                            // Install/activate ACF5 Pro
                            $.post( window.location.pathname, {
                                oxygen_acf5_activate : 1
                            }, function( response ) {
                                if ( response.length > 2 ) {
                                    response = $.trim( response.replace( /.*>/ig, '' ) );
                                }

                                if ( response == '1' ) {
                                    acf5Activated = true;
                                    $acfInstaller.html( '&#10003; Great! Please refresh the page' ).removeClass( 'disabled' ).addClass( 'done button-primary' );
                                } else if ( response == '-1' ) {
                                    //show success
                                    $acfInstaller.html( 'ACF5 could not be activated!' );
                                    $acfInstaller.after( ' <span>Please try activating the plugin manually from Plugins page.</span>' );
                                } else {
                                    $acfInstaller.html( 'ACF5 could not be installed!' );
                                    $acfInstaller.after( ' <span>Please try installing the plugin manually from <strong>Appearance &gt; Install Plugins</strong> page.</span>' );
                                }
                            } );
                        } else {
                            $acfInstaller.html( 'ACF4 plugin could not be deactivated!' );
                            $acfInstaller.after( ' <span>Please try deactivating the plugin manually from Plugins page.</span>' );
                        }
                    } );
                } );
            }
        } );
    } );
} )( jQuery, window );