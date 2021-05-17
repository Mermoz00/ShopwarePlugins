{extends file="parent:backend/_base/layout.tpl"}

{block name="content/main"}
    <div class="page-header">
        <h3 style="margin-top: 0px;margin-bottom: 0px;">3D Bilder</h3>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Path</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                {foreach $suppliers as $supplier}
                    <tr>
                        <td>{$supplier.id}</td>
                        <td>{$supplier.name}</td>
                        <td>{$supplier.path}</td>
                        <td>
                            <a href="{url controller="SpkThree" action="delete" __csrf_token=$csrfToken id = $supplier.id}" class="btn btn-danger a-btn-slide-text">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                {/foreach}
            </tbody>
            <tfoot>
                <tr class="active">
                    <td colspan="4">
                        <strong>Total:</strong> {$totalSuppliers}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
{/block}
