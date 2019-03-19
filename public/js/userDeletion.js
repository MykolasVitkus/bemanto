function filterTable(event) {
            var filter = event.target.value.toUpperCase();
            var rows = document.querySelector("#dataTable").rows;

            for (var i = 0; i < rows.length; i++) {
                var firstCol = rows[i].cells[0].textContent.toUpperCase();
                var secondCol = rows[i].cells[1].textContent.toUpperCase();
                var thirdCol = rows[i].cells[2].textContent.toUpperCase();
  
                if (firstCol.indexOf(filter) > -1 || secondCol.indexOf(filter) > -1 || thirdCol.indexOf(filter) > -1 ) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
        }

       document.querySelector('#myInput').addEventListener('keyup', filterTable, false);

