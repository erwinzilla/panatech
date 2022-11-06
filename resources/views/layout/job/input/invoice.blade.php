@include('layout.invoice.job',
[
    'data'              => $data_additional['invoice'],
    'job_id'            => $data->id,
    'data_additional'   => $data_additional['invoice_item'],
])