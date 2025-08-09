<?php

namespace croacworks\yii2basic\widgets;

use croacworks\yii2basic\controllers\rest\ControllerCustom;
use croacworks\yii2basic\models\Configuration;
use croacworks\yii2basic\themes\adminlte3\assets\PluginAsset;
use Yii;
use yii\web\View;

class UploadFoto extends \yii\bootstrap5\Widget
{
  public $aspectRatio = '35/22';
  public $reloadGrid = [];
  public $fileField = 'file';
  public $fileName = 'file';
  public $imagem = '';
  public $type = ' image/*';
  public $view;
  public $maxSize = 2;
  public $maxWidth = 1000;

public function init(): void
  {
    PluginAsset::register(Yii::$app->view)->add(['cropper']);
    $config = Configuration::get();
    $maxSize = $config->getParameters()->where(['name' => 'max_upload_size'])->one();
    if ($maxSize !== null)
      $this->maxSize = $maxSize->value;
  }

  /**$
   * {@inheritdoc}
   */
  public function run()
  {
    $emptyImage = '/dummy/code.php?x=250x250/fff/000.jpg&text=NO IMAGE SELECTED';
    $showRemove = '';
    $assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/croacworks/yii2basic/src/themes/adminlte3/web/dist');
    if (empty($this->imagem)) {
      $this->imagem = $emptyImage;
      $showRemove = 'd-none';
    }

    $reload = '';
    if (array_count_values($this->reloadGrid) > 0) {
      foreach ($this->reloadGrid as $item) {
        $reload .= "$.pjax.reload({container: '#{$item}', async: false});";
      }
    }

    $script = <<<JS
      var tmp_file = null;
      var banner = document.getElementById('photo_x');
      var remove = document.getElementById('remove');
      var btn_remove = document.getElementById('btn-remove');
      var image = document.getElementById('image_x');
      var input = document.getElementById('image_upload_x');
      var file_field = document.getElementById('$this->fileField');
      var modal = $('#modal');
      var cropper;

    /**
     * Compress an image to be smaller than the max file size using Canvas API.
     * @param {File} file - The image file to compress.
     * @param {number} maxSize - The maximum file size in bytes.
     * @returns {Promise<File>} A promise that resolves with the compressed image file.
     */
    function compressImage(file, maxSize) {
      return new Promise((resolve, reject) => {
        //resolve(file);
        const reader = new FileReader();

        // Load the image file
        reader.readAsDataURL(file);
        reader.onload = (event) => {
          const img = new Image();
          img.src = event.target.result;

          img.onload = () => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            // Set canvas dimensions to the image dimensions
            let width = img.width;
            let height = img.height;

            // Scale down the image dimensions if needed
            const maxDimension = {$this->maxWidth}; // Max dimension (width or height) after scaling
            if (width > maxDimension || height > maxDimension) {
              if (width > height) {
                height = Math.floor((height * maxDimension) / width);
                width = maxDimension;
              } else {
                width = Math.floor((width * maxDimension) / height);
                height = maxDimension;
              }
            }

            // Set the canvas size and draw the scaled image
            canvas.width = width;
            canvas.height = height;
            ctx.drawImage(img, 0, 0, width, height);

            // Get the compressed image data
            canvas.toBlob((blob) => {

              if (blob.size > {$this->maxSize} * 1024 * 1024) {
                reject(new Error('Image exceeds 5MB limit even after compression.'));
              } else {
                // Ensure the compressed image size is below the maxSize
                const compressedFile = new File([blob], file.name, {
                  type: file.type,
                  lastModified: Date.now()
                });
                resolve(compressedFile); // Return the compressed file
              }

            }, file.type, 0.8); // Adjust the quality (0.8 is 80%)
          };

          img.onerror = (error) => {
            reject('Error loading image');
          };
        };

        reader.onerror = (error) => {
          reject('Error reading file' );
        };
      });
    }

    function encodeImageFileAsURL(file,preview) {
        return new Promise((resolve, reject) => {
            var reader = new FileReader();            
            reader.onloadend = function () {
                preview.src = reader.result;
                resolve(); // Resolving the promise when the encoding is complete
            };
            reader.onerror = reject; // Rejecting the promise in case of an error
            reader.readAsDataURL(file);
        });
    }

    function formatFileSize(bytes) {
      if (bytes === 0) return '0 Bytes';
      const k = 1024; // Define the constant for kilobyte
      const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB']; // Units
      const i = Math.floor(Math.log(bytes) / Math.log(k)); // Determine the unit index
      return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function isImage(file) {
      const fileType = file.type;
      const validImageTypes = ["image/jpeg", "image/png", "image/gif", "image/bmp", "image/webp"];
      if (validImageTypes.includes(fileType)) {
        return true;
      }
      return false;
    }

    window.addEventListener('DOMContentLoaded', function () {

      $('[data-toggle="tooltip"]').tooltip();

      btn_remove.addEventListener('click', function (e) {
        banner.src = '{$emptyImage}';
        file_field.files = null;
        remove.value = 1;
      });

      input.addEventListener('change', function (e) {
        $('#overlay-foto').show();
        
        var files = e.target.files;

        if (files && files.length > 0) {
          tmp_file = files[0];

          const maxSizeInBytes = {$this->maxSize} * 1024 * 1024;
          if (tmp_file.type == 'image/png' && tmp_file.size > maxSizeInBytes) {
            $('#overlay-foto').hide();
            alert('Image exceeds {$this->maxSize}MB limit.');
            return false;
          } else if(tmp_file.type == 'image/png') {
      
            encodeImageFileAsURL(tmp_file,image).then((blob) => {
              modal.modal('show');
              $('#overlay-foto').hide();
            }).catch((error) => {
              $('#overlay-foto').hide();
              return false;
            });
            
          }else{

            compressImage(tmp_file).then((blob) => {

              let file_compressed = new File([blob], tmp_file.name , { type: tmp_file.type, lastModified: new Date().getTime() });
              let container = new DataTransfer();
              container.items.add(file_compressed);
              file_field.value = '';
              file_field.files = container.files;
              image.src = URL.createObjectURL(blob);
              modal.modal('show');
              $('#overlay-foto').hide();
              return true;
            }).catch((error) => {
              $('#overlay-foto').hide();
              alert(error);
              return false;
            });
          }

        }

      });

      modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
          initialAspectRatio: $this->aspectRatio,
          aspectRatio: $this->aspectRatio,
          viewMode: 2,
        });
      });

      modal.on('hidden.bs.modal', function (event) {
        input.value = '';
        if(cropper !== null && cropper !== undefined)
          cropper.destroy();
      })

      document.getElementById('cancelar').addEventListener('click', function () {
        modal.modal('hide');
      });

      document.getElementById('crop').addEventListener('click', function () {
        var initialAvatarURL;
        var canvas;
        $('#overlay-foto').show();
        if (cropper) {

          canvas = cropper.getCroppedCanvas();
          initialAvatarURL = banner.src;
          banner.src = canvas.toDataURL();

          canvas.toBlob(function (blob) {            
            let file = new File([blob], tmp_file.name, { type: blob.type, lastModified: new Date().getTime() });
            compressImage(file).then((blob) => {
              let file_compressed = new File([blob], tmp_file.name, { type: blob.type, lastModified: new Date().getTime() });
              let container = new DataTransfer();
              container.items.add(file_compressed);
              file_field.value = '';
              file_field.files = container.files;
              image.src = URL.createObjectURL(blob);
              $('#overlay-foto').hide();
              return true;
            }).catch((error) => {
              $('#overlay-foto').hide();
              alert(error);
              return false;
            });

          });
        }
        cropper.destroy();
        cropper = null;
        modal.modal('hide');
      });
    });
    JS;

    $css = <<< CSS
        .label-file {
            padding: 20px 10px;
            width: 30%;
            text-align: center;
            display: block;
            margin-top: 10px;
            cursor: pointer;
            margin: 0 auto;
        }
        input[type=file] {
            display: none;
        }
        .img-container img {
          width: 100%;
          max-height: 80vh;
        }
        #photo{
            width: 60%;
            margin: 0 auto;
            padding: 5px;
        }
    CSS;

    \Yii::$app->view->registerCss($css);
    \Yii::$app->view->registerJs($script, View::POS_END);

    $html = <<<HTML
        <div class="card">
          <div class="card-body">

            <div id='overlay-foto' class='overlay' style='display:none;height: 100%;position: absolute;width: 100%;z-index: 3000;top: 0;left: 0;background: #0000004f;'>
                <div class='d-flex align-items-center'>
                    <strong> <?= Yii::t('app', 'Processing...') ?></strong>
                    <div class='spinner-border ms-auto' role='status' aria-hidden='true'></div>
                </div>
            </div>

            <div class="col-sm-12 text-center pb-1">
                <img class="rounded" id="photo_x" src="$this->imagem" style="max-width:600px;" alt="banner">
            </div>

            <div class="col-sm-12 text-center pb-1">
                <label class="label-file w-10 btn-weebz" style="width:250px;" data-toggle="tooltip" title="Selecione a Imagem">
                   <i class="fas fa-file-upload"></i> Selecione a Imagem
                   <input type="file" class="sr-only" id="image_upload_x" name="image_upload_x" accept="image/*">
                   <input type="file" class="sr-only" id="$this->fileField" name="$this->fileName" accept="image/*">
                   <input type="hidden" id="remove" name="remove" value="0">
                </label>
            </div>

            <div class="col-sm-12 text-center pb-2">
                <a href="javascript:;" id="btn-remove"  style="width:250px;" class="btn label-file btn-danger {$showRemove}"><i class="fas fa-trash"></i> Remover</a>
            </div>

          </div>
        </div>

        <div class="modal fade" id="modal" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="modalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Cortar imagem</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body" style="height:500px!importante">
                <div class="img-container">
                  <img id="image_x" src="{$emptyImage}">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelar" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="crop">Crop</button>
              </div>
            </div>
          </div>
        </div>

    HTML;
    echo $html;
  }
}