<?php
/**
 * Phanbook : Delightfully simple forum software
 *
 * Licensed under The GNU License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @link    http://phanbook.com Phanbook Project
 * @since   1.0.0
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 */
namespace Phanbook\Backend\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Radio;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Textarea;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Identical;
use Phanbook\Models\Province;

class PostsForm extends Form
{
    public function initialize($entity = null)
    {
        // In edit page the id is hidden
        if (!is_null($entity)) {
            $this->add(new Hidden('id'));
        }

        //title
        $title = new Text(
            'title',
            array(
            'placeholder' => t('Title'),
            'class'       => 'form-control',
            'required'    => true
            )
        );
        $title->addValidator(
            new PresenceOf(
                array(
                'message' => t('The title is required.')
                )
            )
        );
        $this->add($title);

        // In edit page the id is hidden
        if (!empty($entity)) {
            $checked = null;
            $this->add(new Radio('locked', ['value' => 'Y', 'checked' => $checked, 'name' => 'locked']));
            if ($entity->getLocked() == 'N') {
                $checked = 'checked';
            }
            $this->add(new Radio('unLocked', ['value' => 'N', 'checked' => $checked, 'name' => 'locked']));
        } else {
            $this->add(new Radio('locked', ['value' => 'Y', 'name' => 'locked']));
            $this->add(new Radio('unLocked', ['value' => 'N', 'name' => 'locked', 'checked' => '']));
        }

        //content
        $content = new Textarea(
            'content',
            [
                'data-provide'=> 'markdown',
                'data-iconlibrary' => 'fa',
                'required'    => true,
                'rows'  =>15
            ]
        );
        $content->addValidator(
            new PresenceOf(
                array(
                'message' => t('content is required.')
                )
            )
        );
        $this->add($content);
        $tags = new Text(
            'tags',
            [
                'placeholder' => t('Tags'),
                'class'       => 'form-control',
                'required'    => true
            ]
        );
        $this->add($tags);
        $this->add(new Hidden('object'));

        // To compare the post is question or tip
        $this->add(new Hidden('type'));
        // CSRF
        $csrf = new Hidden('csrf');
        $this->add($csrf);

        $this->add(
            new Submit(
                'save',
                array(
                'class' => 'btn btn-sm btn-success pull-right',
                'value' => t('Submit Post')
                )
            )
        );
        $this->add(
            new Submit(
                'saveDraft',
                [
                    'class' => 'btn btn-sm btn-primary pull-left',
                    'value' => t('Save Draft')
                ]
            )
        );
    }
}
