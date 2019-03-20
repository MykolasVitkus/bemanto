let infoBlocks = document.querySelectorAll('#info-block');
let menuItems = document.querySelectorAll("#menu-item");

menuItems.forEach((menuItem, index) => {
    menuItem.addEventListener(('click'), () => {
        showBlock(index);
    });
})

showBlock = (id) =>
{
    menuItems.forEach((menuItem) => {
        menuItem.classList.remove('active');
    })
    menuItems[id].classList.toggle('active');

    infoBlocks.forEach((infoBlock) => {
        infoBlock.classList.add('hide');
    })
    infoBlocks[id].classList.remove('hide');
}