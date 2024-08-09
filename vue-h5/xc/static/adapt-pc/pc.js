;(function () {
  if (window.innerWidth < 768) {
    return
  }
  var tpl = document.querySelector('#tpl-adapt-pc').innerHTML || ''
  tpl = tpl.replace('helang-mobile-href', window.location.href)
  document.querySelector('uni-adapt-pc').innerHTML = tpl
  document.body.setAttribute('adapt', 'pc')
})()
