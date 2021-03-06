/*
 * File: app/view/MyViewportViewController.js
 *
 * This file was generated by Sencha Architect version 3.5.1.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 6.0.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 6.0.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('MyApp.view.MyViewportViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.myviewport',

    onSave: function(button, e, eOpts) {
        /*var form = this.getReferences().form,
        values = form.getForm().getValues(),
        store = this.getStore('recipes');

        // Valid
        if (form.isValid()) {

            // TODO: Assign the record's ID from data source
            // Normally, this value would be auto-generated,
            // or returned from the server
            values.id = store.count() + 1;
            // Save record
            store.add(values);
            // Commit changes
            store.commitChanges();
            // Complete
            form.close();

        }*/
        var form = this.getReferences().form;
        if (form.isValid()) {

            form.submit({
                method: 'POST',

                waitMsg:'Saving...',

                success: function(form, action) {
                    Ext.Msg.alert('Éxito', action.result.message);

                },

                failure: function(form, action) {


                    Ext.Msg.alert('Failed action', action.result);
                    Ext.getCmp('formname').getForm().setValues({
                        fielda: 'value1',
                        fieldb: 'value2'
                    });

                }
            });
        }else{
            Ext.Msg.alert('Formulario inválido', 'Los datos suministrados en el formulario son invalidos');;
        }
    },

    onCancel: function(button, e, eOpts) {
        //var form = this.getReferences().form;
        //form.close();
        Ext.Msg.alert('Cancel', 'Ha cancelado');
    }

});
