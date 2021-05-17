{extends file="parent:backend/_base/layout.tpl"}

{block name="content/main"}
    {if $data['success']==true}
        <!-- Primary Alert -->
        <div class="alert alert-success alert-dismissible">
            <strong>Success!</strong> {$data['data']['message']}.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    {/if}
    {if $data['error']==true }
        <!-- Error Alert -->
        <div class="alert alert-danger alert-dismissible">
            <strong>Error!</strong> {$data['data']['message']}.
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    {/if}

    <div class="panel panel-default">

        <div class="panel-heading"><h3 class="panel-title">Upload eines 3D-Objekts</h3></div>

        <div class="panel-body">
            <form class="form-horizontal" action="{url controller="SpkThree" action="upload" __csrf_token=$csrfToken}" method="post" enctype="multipart/form-data">

                <div class="form-group">
                    <label class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="fileId" id="fileId" required placeholder="Bitte Namen für das 3D-Objekt eingeben...">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Datei auswählen</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control-file" name="file" id="file" required placeholder="Datei einfügen...">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Hochladen</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
{/block}
