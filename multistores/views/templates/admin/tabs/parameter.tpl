{* Header *}
<h3><i class="icon-cogs"></i> {l s='Parameters' mod='multistores'}</h3>
<form role="form" action="#" method="POST" id="parameter_form" name="parameter_form">

{* Body *}
<h4>{l s='General parameters' mod='multistores'}</h4>
<table id="example" class="table table-striped table-bordered">
    <thead>
        <tr > 
            <th>{l s='Store' mod='multistores'}</th>
            <th>{l s='City' mod='multistores'}</th>
            <th>{l s='Employees' mod='multistores'}</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$stores item=store}
            <tr>
                <td><h4>{$store.name}</h4></td>
                <td>{$store.city}</td>
                <td>
                    <div class="form-check">
                        {foreach from=$employee item=employees}
                        <input type="checkbox" id="{$employees.id_employee}" name="employee" value="{$employees.id_employee}">
                        <label for="{$employees.id_employee}">{$employees.lastname} {$employees.firstname}</label><br>
                        {/foreach}
                    </div>      
                </td> 
            </tr>
        {/foreach}
    </tbody>
</table>
<div class="clearfix"></div>

{* Footer avec les actions *}
<div class="panel-footer">
    <div class="btn-group pull-right">
        <button name="submitParameters" id="submitParameters" type="submit" class="btn btn-default">
            <i class="process-icon-save"></i>
            {l s='Save' mod='multistores'}
        </button>
    </div>
</div>
 
</form>