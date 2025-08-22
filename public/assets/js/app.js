
document.querySelectorAll('[data-modal-close]').forEach(b=>b.addEventListener('click',()=>{
  document.querySelector('#modal').classList.remove('show');
}));
