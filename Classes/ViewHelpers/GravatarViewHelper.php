<?php
namespace Flownative\Gravatar\ViewHelpers;

/*
 * This file is part of the Flownative.Gravatar package.
 *
 * (c) Robert Lemke, Flownative GmbH - www.flownative.com
 */

use Neos\FluidAdaptor\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * A view helper to display a Gravatar
 *
 * You may specify the size (width = height) in pixels and a fallback / default image URL. Instead of a URL, the
 * "default" parameter can also be one of "404", "mm", "identicon", "monsterid", "wavatar", retro" or "blank".
 *
 * See https://en.gravatar.com/site/implement/images/ for a full explanation of these options.
 *
 * = Examples =
 *
 * <code title="Simple">
 * <flownative:gravatar email="{emailAddress}" default="http://domain.com/gravatar_default.png" class="gravatar" />
 * </code>
 *
 * Output:
 * <img class="gravatar" src="http://www.gravatar.com/avatar/<hash>?d=http%3A%2F%2Fdomain.com%2Fgravatar_default.png" />
 *
 */
class GravatarViewHelper extends AbstractTagBasedViewHelper {

    /**
     * @var string
     */
    protected $tagName = 'img';

    /**
     * Initialize arguments
     *
     * @return void
     */
    public function initializeArguments() {
        parent::initializeArguments();
        $this->registerTagAttribute('alt', 'string', 'Specifies an alternate text for an image', false);
        $this->registerTagAttribute('width', 'string', 'Specifies the image width', false);
        $this->registerTagAttribute('height', 'string', 'Specifies the image height', false);
        $this->registerArgument('email', 'string', 'Gravatar Email', true);
        $this->registerArgument('default', 'string', 'Default URL if no Gravatar was found');
        $this->registerArgument('size', 'Integer', 'Size of the Gravatar');

        $this->registerUniversalTagAttributes();
    }

    /**
     * Render the link.
     *
     * @return string The rendered link
     */
    public function render() {
        $sanitizedEmail = strtolower(trim((string)$this->arguments['email']));
        $gravatarUri = 'https://www.gravatar.com/avatar/' . md5($sanitizedEmail);
        $uriParts = array();
        if ($this->arguments['default']) {
            $uriParts[] = 'd=' . urlencode($this->arguments['default']);
        }
        if ($this->arguments['size']) {
            $uriParts[] = 's=' . $this->arguments['size'];
            if (!isset($this->arguments['width']) && !isset($this->arguments['height'])) {
                $this->tag->addAttribute('width', $this->arguments['size']);
                $this->tag->addAttribute('height', $this->arguments['size']);
            }
        }
        if (!isset($this->arguments['alt'])) {
            $this->tag->addAttribute('alt', 'Gravatar');
        }
        if (count($uriParts)) {
            $gravatarUri .= '?' . implode('&', $uriParts);
        }
        $this->tag->addAttribute('src', $gravatarUri);
        return $this->tag->render();
    }
}
