<?php

function presigned_url($filename){
  if ( env('APP_FILESYSTEM') == 's3' ){
    $s3 = \Storage::disk('s3');
    $client = $s3->getDriver()->getAdapter()->getClient();
    $expiry = "+10 minutes";

    $command = $client->getCommand('GetObject', [
       'Bucket' => \Config::get('filesystems.disks.s3.bucket'),
       'Key'    => $filename,
       'ResponseContentDisposition' => 'attachment;'
    ]);

    $request = $client->createPresignedRequest($command, $expiry);

    return (string) $request->getUri();

  }else{
    return (string) \Storage::url($filename);
  }

}
