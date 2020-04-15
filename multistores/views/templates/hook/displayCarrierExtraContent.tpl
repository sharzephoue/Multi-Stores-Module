{foreach from=$stores item=store}
<div id='store' class="row">
    <div id='store_img' class="col-md-5">
        <img class="storespics" src="{$store.image.bySize.stores_default.url}" alt="{$store.name}"
            title="{$store.name}">
    </div>
    <div id='store_description' class="col-md-5 pb-2">
        <h4>{$store.name}</h4>
        <h5>{$store.phone}{$store.fax}{$store.email}</h5>
        <p> {$store.address1}<br />
            {$store.city} {$store.postcode}
        </p>
        <div class="dropdown mb-1">
            <button class="btn dropdown-toggle" type="button" id="{$store.name}"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {l s='Working hours' mod='multistores'}
            </button>
            <div class="dropdown-menu" aria-labelledby="{$store.name}">
                <table class="m-1">
                    {foreach $store.business_hours as $day}
                    <tr>
                        <th>{$day.day|truncate:4:'.'}</th>
                        <td>
                            <ul class="mx-0 my-0">
                                {foreach $day.hours as $h}
                                <li>{$h}</li>
                                {/foreach}
                            </ul>
                        </td>
                    </tr>
                    {/foreach}
                </table>
            </div>
        </div>
        <button type="submit" id="{$store.id_store}" class="btn btn-primary" name="confirmDeliveryOption" value="{$store.name}">{l s='Select'
            mod='multistores'}</button>
    </div>
</div>
{/foreach}