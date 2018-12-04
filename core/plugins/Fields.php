<?php

namespace PhalartCMS\Plugin;


class Fields
{
    public $data;

    public function __construct($dataOld)
    {
        $this->data = $dataOld;
    }

    public function render($data)
    {
        if(isset($this->data[$data['key']])) {
            $data['value'] = $this->data[$data['key']];
        } else if (!isset($data['value'])) {
            $data['value'] = '';
        }

        $data['key_single'] = $data['key'];
        if(isset($data['key_parent']) && !empty($data['key_parent'])) {
            $data['key'] = $data['key_parent'] . "[{$data['key']}]";
        }



        switch ($data['type']) {
            case 'text':
                return $this->text($data);
                break;
            case 'single_image':
                return $this->single_image($data);
                break;
            case 'textarea':
                return $this->textarea($data);
                break;
            case 'submit':
                return $this->submit($data);
                break;
            case 'image_radio':
                return $this->image_radio($data);
                break;
            case 'toggle_event':
                return $this->toggle_event($data);
                break;
            default:
                return '';
        }
    }

    public function image_radio($data)
    {
        ob_start();



        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="layout">Layout</label>
            <div class="col-sm-9">
                <?php foreach ($data['options'] as $_key => $value) : ?>
                    <label for="<?php echo $data['key'] . $value['value'] ?>">
                        <input class="no-display" type="radio" id="<?php echo $data['key'] . $value['value'] ?>"
                               name="<?php echo @$data['key'] ?>"
                               value="<?php echo $value['value'] ?>"
                               <?php echo $value['value'] == @$this->data[$data['key_single']] ? 'checked' : '' ?>
                        >
                        <img style="width: 150px;" src="<?php echo $value['src'] ?>" alt="">
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    public function submit($data)
    {
        ob_start();
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="form-field-6">
            </label>
            <div class="col-sm-9">
                <button class="btn btn-success" type="submit"><?php echo $data['title'] ?></button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function text($data)
    {
        ob_start();
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="form-field-6">
                <b><?php echo $data['title'] ?></b>
            </label>
            <div class="col-sm-9">
                <input type="text" value="<?php echo $data['value'] ?>" name="<?php echo @$data['key'] ?>" placeholder="Text Field" id="form-field-6" class="form-control">

                <?php if (isset($data['desc']) && !empty($data['desc'])) : ?>
                    <span class="help-block"><i class="fa fa-info-circle"></i><?php echo $data['desc'] ?></span>
                <?php endif; ?>

            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function textarea($data)
    {
        ob_start();
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="form-field-6">
                <b><?php echo $data['title'] ?></b>
            </label>
            <div class="col-sm-9">
                <textarea class="form-control" name="<?php echo @$data['key'] ?>" id="" cols="30" rows="5"><?php echo $data['value'] ?> </textarea>

                <?php if (isset($data['desc']) && !empty($data['desc'])) : ?>
                    <span class="help-block"><i class="fa fa-info-circle"></i><?php echo $data['desc'] ?></span>
                <?php endif; ?>

            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    public function single_image($data)
    {
        ob_start();

        $name = @$data['key'];
        $urlAjax = @$data['url_upload'];

        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="form-field-6">
                <b><?php echo $data['title'] ?></b>
            </label>
            <div class="col-sm-9">
                <div style="position: relative; width: 200px;" class="wrapUploadSingle">
                    <div style="display: none">
                        <input
                            class="pull-left uploadSingle"
                            type="file"
                            id="uploadImageShare_<?php echo $name ?>"
                            data-resize="<?php echo (isset($data['resize']) ? $data['resize'] : 0) ?>"
                            data-targetpreview="#preview_<?php echo $name ?>"
                            data-wrap=".wrapUploadSingle"
                            data-nameinput="#<?php echo $name ?>_reciver"
                            data-endpoint="<?php echo $urlAjax ?>"
                        >
                    </div>

                    <div class="previewImage btn" id="preview_<?php echo $name ?>" onclick="$('#uploadImageShare_<?php echo $name ?>').trigger('click')">
                        <img class="" src="/<?php echo $data['value'] ?>" alt="">
                        <input type="hidden" name="<?php echo $name ?>" value="<?php echo $data['value'] ?>" id="<?php echo $name ?>_reciver">
                    </div>

                    <div class="progress-wrp">
                        <div class="progress-bar"></div>
                        <div class="status">0%</div>
                    </div>

                </div>
            </div>
        </div>

        <?php
        return ob_get_clean();
    }

    public function toggle_event($data)
    {
        ob_start();
        if ($data['value'] == '') {
            $data['value'] = 'false';
        }
        ?>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="active">On Off ChatNhanh</label>
            <div class="col-sm-9">
                <label>
                    <input type="checkbox" <?php echo ($data['value'] == 'true') ? 'checked' : '' ?> id="toggle-event" data-toggle="toggle">
                </label>
                <input type="hidden" name="<?php echo @$data['key'] ?>" id="<?php echo @$data['key'] ?>" value="<?php echo ($data['value'] == '') ? 'false' : $data['value'] ?>" />
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}