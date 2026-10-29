/* globals acf jQuery */

(function ($) {
  const select2init = function () {
    acf.addAction('select2_init', function ($select, args, settings, field) {
      const $fieldEl = field.$el

      if ($fieldEl.data('name') === 'reusableComponent') {
        // Use both ACF and jQuery events for better compatibility
        acf.addAction('change', function ($input) {
          if ($input.closest('[data-name="reusableComponent"]').length) {
            updateReusableComponentLink($input, $fieldEl)
          }
        })

        // Also listen for jQuery change event as backup
        $select.on('change', function () {
          updateReusableComponentLink($(this), $fieldEl)
        })
      }
    })
  }

  function updateReusableComponentLink($input, $fieldEl) {
    const postId = $input.val()
    const postTitle = $input.find('option:selected').text()
    const $postLink = $fieldEl.find('.reusable-postLink')
    const oldPostId = $postLink.attr('data-postId')
    const $hiddenEl = $fieldEl.find('[hidden]')
    const href = $postLink.attr('href')

    console.log('Select changed:', { postId, postTitle, oldPostId })

    if (postId && postTitle) {
      $hiddenEl.removeAttr('hidden')
      $postLink.text(postTitle)
      $postLink.attr('data-postId', postId)
      if (href && oldPostId) {
        $postLink.attr('href', href.replace(oldPostId, postId))
      }
    }
  }

  if (typeof acf !== 'undefined') {
    select2init()
  }
})(jQuery)
