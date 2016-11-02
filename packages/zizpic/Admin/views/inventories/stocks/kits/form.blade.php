<div class='box col-md-12' v-attr='class: (part.missedQuantity == 0 ? "box col-md-10 box-success" : "box col-md-10")' v-repeat="part: parts">
    <div class="box-header with-border">
        <h3 class='box-title' v-model='partQuantityValidator'>@{{ part.name }} (@{{part.usedQuantity}}/@{{part.pivot.quantity}})</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class='box-body'>
        <table class='table table-bordered table-hover kit-stocks-datatable'>
            <thead>
                <tr>
                    <td>
                        Available Quantity
                    </td>
                    <td>
                        S/N
                    </td>
                    <td>
                        Take Quantity
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr v-repeat='stock: part.stocks'>
                    <td>
                        @{{stock.quantity-stock.usedQuantity}}
                    </td>
                    <td>
                        @{{stock.serial_no}}
                    </td>
                    <td>
                        <input name='stock_quantity[]' type='number' min='0' v-attr='max: (part.missedQuantity == 0 ? stock.usedQuantity : part.usedQuantity+part.missedQuantity) , readonly: ((part.missedQuantity == 0 && stock.usedQuantity == 0) || (stock.usedQuantity == 0 && stock.quantity == 0))' v-model='stock.usedQuantity | partQuantityValidator' />
                        <input v-model='stock.id' name='stock_id[]' type='hidden' />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="form-group">
    <div class="col-sm-offset-1 col-sm-10">
        {!! Form::submit('Save', ['class'=>'btn btn-primary']) !!}
    </div>
</div>