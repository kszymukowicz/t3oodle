<?php declare(strict_types=1);
namespace T3\T3oodle\ViewHelpers;

/*  | The t3oodle extension is made with ❤ for TYPO3 CMS and is licensed
 *  | under GNU General Public License.
 *  |
 *  | (c) 2020 Armin Vieweg <info@v.ieweg.de>
 */
use T3\T3oodle\Domain\Permission\PollPermission;
use T3\T3oodle\Utility\UserIdentUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

class PermissionViewHelper extends AbstractViewHelper
{
    use CompileWithRenderStatic;

    /**
     * @var PollPermission
     */
    private static $permission;

    public function initializeArguments()
    {
        $this->registerArgument('permissionClassName', 'string', '', false, PollPermission::class);
        $this->registerArgument('poll', 'object', 'Poll object', false);
        $this->registerArgument(
            'action',
            'string',
            'Name of action to ask for permissions, e.g. "voting" or "edit"',
            true
        );
        $this->registerArgument('negate', 'bool', 'Negates the result, when true', false, false);
    }

    private static function init(array $arguments)
    {
        if (!self::$permission) {
            $currentUserIdent = UserIdentUtility::getCurrentUserIdent();
            self::$permission = GeneralUtility::makeInstance($arguments['permissionClassName'], $currentUserIdent);
        }
    }

    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        self::init($arguments);

        $poll = $arguments['poll'] ?? $renderChildrenClosure();

        $status = self::$permission->isAllowed($poll, $arguments['action']);
        if ($arguments['negate']) {
            return !$status;
        }
        return $status;
    }
}
