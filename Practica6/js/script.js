// Dibujar un rectángulo en el centro de la pantalla
var canvas = document.getElementById('myCanvas');
var ctx = canvas.getContext('2d');
var centerX = canvas.width / 2;
var centerY = canvas.height / 2;
var rectWidth = 295;
var rectHeight = 135;

ctx.fillStyle = 'white';
ctx.fillRect(centerX - rectWidth / 2, centerY - rectHeight / 2, rectWidth, rectHeight);

// Hacer el botón arrastrable con Interact.js
interact('.equipment-button')
    .draggable({
        listeners: {
            move: function (event) {
                var target = event.target;
                var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
                target.setAttribute('data-x', x);
                target.setAttribute('data-y', y);
            }
        }
    });

fetch('http://localhost/Practica6/php/conexion.php/ComputerInventory')
    .then(response => response.json())
    .then(computerInventoryData => {
        fetch('http://localhost/Practica6/php/conexion.php/ComputerState')
            .then(response => response.json())
            .then(computerStateData => {
                console.log('Computer Inventory Data:', computerInventoryData);
                console.log('Computer State Data:', computerStateData);

                const equipmentContainer = document.getElementById('equipment-container');
                const infoContainer = document.getElementById('info-container');

                let availableCount = 0;
                let occupiedCount = 0;
                let outOfServiceCount = 0;

                computerInventoryData.forEach(computer => {
                    const button = document.createElement('button');
                    button.classList.add('equipment-button');

                    const state = computerStateData.find(state => state.ci_id === computer.id);
                    let estado = state ? state.estado : 'Fuera de servicio';

                    if (estado === 'Disponible') {
                        button.classList.add('estado-disponible');
                        availableCount++;
                    } else if (estado === 'Ocupado') {
                        button.classList.add('estado-ocupado');
                        occupiedCount++;
                    } else {
                        button.classList.add('estado-fuera-de-servicio');
                        outOfServiceCount++;
                    }

                    button.dataset.id = computer.id;

                    let clickCount = 0; // Variable para contar los clics en el botón

                    button.addEventListener('click', () => {
                        clickCount++; // Incrementar el contador de clics

                        if (clickCount === 3) { // Verificar si el contador de clics es igual a 3
                            if (estado === 'Disponible') {
                                estado = 'Ocupado';
                                button.classList.remove('estado-disponible');
                                button.classList.add('estado-ocupado');
                                availableCount--;
                                occupiedCount++;
                            } else if (estado === 'Ocupado') {
                                estado = 'Fuera de servicio';
                                button.classList.remove('estado-ocupado');
                                button.classList.add('estado-fuera-de-servicio');
                                occupiedCount--;
                                outOfServiceCount++;
                            } else {
                                estado = 'Disponible';
                                button.classList.remove('estado-fuera-de-servicio');
                                button.classList.add('estado-disponible');
                                outOfServiceCount--;
                                availableCount++;
                            }

                            fetch(`http://localhost/Practica6/php/conexion.php/ComputerState/${computer.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ estado })
                            })
                                .then(response => response.json())
                                .then(data => console.log(data));

                            infoContainer.innerHTML = `
                                 <p>Total de equipos: ${computerInventoryData.length}</p>
                                 <p>Equipos disponibles: ${availableCount}</p>
                                 <p>Equipos ocupados: ${occupiedCount}</p>
                                 <p>Equipos fuera de servicio: ${outOfServiceCount}</p>
                             `;

                            clickCount = 0; // Reiniciar el contador de clics después del tercer clic
                        }
                    });

                    button.addEventListener('mouseover', () => {
                        const infoPopup = document.createElement('div');
                        infoPopup.classList.add('info-popup');
                        infoPopup.innerHTML = `
                            <p><strong>Número PC:</strong> ${computer.numeroPC}</p>
                            <p><strong>Número de Serie:</strong> ${computer.numeroSerie}</p>
                            <p><strong>Marca:</strong> ${computer.marca}</p>
                            <p><strong>Con Mouse:</strong> ${computer.conMouse === '1' ? 'Sí' : 'No'}</p>
                            <p><strong>Con Teclado:</strong> ${computer.conTeclado === '1' ? 'Sí' : 'No'}</p>
                            <p><strong>Estado:</strong> ${estado}</p>
                        `;
                        button.appendChild(infoPopup);
                    });

                    button.addEventListener('mouseout', () => {
                        const infoPopup = button.querySelector('.info-popup');
                        if (infoPopup) {
                            button.removeChild(infoPopup);
                        }
                    });

                    equipmentContainer.appendChild(button);
                });

                infoContainer.innerHTML = `
                    <p>Total de equipos: ${computerInventoryData.length}</p>
                    <p> <img class="img-1" src="img/computer.png" alt=""> Equipos disponibles: ${availableCount}</p>
                    <p> <img class="img-1" src="img/hacker.png" alt=""> Equipos ocupados: ${occupiedCount}</p>
                    <p> <img class="img-1" src="img/troubleshooting.png" alt=""> Equipos fuera de servicio: ${outOfServiceCount}</p>
                `;
            });
    });