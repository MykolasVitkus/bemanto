function showBlock(id)
{
    var infoToShow;

    switch(id)
    {
        case 1:
            infoToShow = "1 block has been shown";
            break;
        case 2:
            infoToShow = "2 block has been shown";
            break;
        case 3:
            infoToShow = "3 block has been shown";
            break;
    }

    document.getElementById('information_block').innerHTML = infoToShow;
}