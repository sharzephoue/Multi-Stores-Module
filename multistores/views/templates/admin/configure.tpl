<div class="bootstrap">
    
    <div class="col-lg-2">
        <div class="list-group">
            <a href="#parameter" class="menu_tab list-group-item active" data-toggle="tab"><i class="icon-cogs"></i> {l s='Parameters' mod='multistores'}</a>
        </div>
        <div class="list-group">
            <a class="list-group-item"><i class="icon-info"></i> {l s='Version' mod='multistores'} {$module_version|escape:'htmlall':'UTF-8'}</a>
        </div>
    </div>
    
    
    <div class="tab-content col-lg-10">
        <div class="tab-pane panel active" id="parameter">
            {include file="./tabs/parameter.tpl"}
        </div>
    </div>
 
</div>