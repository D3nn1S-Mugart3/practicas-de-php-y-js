// Hacer el botón arrastrable con Interact.js
interact('.equipment-button')
    .draggable({
        listeners: {
            move: function (event) {
                var target = event.target;
                var x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
                var y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

                // Mueve el botón con la posición del mouse
                target.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
                target.setAttribute('data-x', x);
                target.setAttribute('data-y', y);
            }
        }
    });

// Variable para contener los elementos de equipo
const equipmentContainer = document.getElementById('equipment-container');

// Obtener datos de los escritorios y mostrarlos en la página
fetch('http://localhost/Practica7/php/conexion.php/Escritorio')
    .then(response => response.json())
    .then(escritorioData => {
        console.log('Escritorio Data:', escritorioData);

        // Contenedor de escritorios
        const escritorioContainer = document.getElementById('escritorio-container');

        // Iterar sobre los datos de los escritorios y crear divs para cada uno
        escritorioData.forEach((escritorio, index) => {
            const div = document.createElement('div');
            div.classList.add('escritorio');
            div.textContent = ""; // Añadir contenido según sea necesario

            // Agregar el div del escritorio al contenedor de escritorios
            escritorioContainer.appendChild(div);
        });
    });

// Obtener datos de los equipos y mostrarlos en la página
fetch('http://localhost/Practica7/php/conexion.php/ComputerInventory')
    .then(response => response.json())
    .then(computerInventoryData => {
        // Obtener datos de estado de los equipos
        fetch('http://localhost/Practica7/php/conexion.php/ComputerState')
            .then(response => response.json())
            .then(computerStateData => {
                console.log('Computer Inventory Data:', computerInventoryData);
                console.log('Computer State Data:', computerStateData);

                // Contenedor para mostrar información de equipos
                const infoContainer = document.getElementById('info-container');

                // Contadores para el estado de los equipos
                let availableCount = 0;
                let occupiedCount = 0;
                let outOfServiceCount = 0;

                // Iterar sobre los datos de los equipos y crear botones para cada uno
                computerInventoryData.forEach(computer => {
                    const button = document.createElement('button');
                    button.classList.add('equipment-button');

                    // Obtener estado del equipo
                    const state = computerStateData.find(state => state.ci_id === computer.id);
                    let estado = state ? state.estado : 'Fuera de servicio';

                    // Agregar clases según el estado del equipo
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

                    // Establecer el ID del equipo en el dataset del botón
                    button.dataset.id = computer.id;

                    let clickCount = 0; // Variable para contar los clics en el botón

                    // Evento click para cambiar el estado del equipo
                    button.addEventListener('click', () => {
                        clickCount++; // Incrementar el contador de clics

                        // Verificar si se hizo un triple clic en el botón
                        if (clickCount === 3) {
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

                            // Actualizar el estado del equipo en la base de datos
                            fetch(`http://localhost/Practica7/php/conexion.php/ComputerState/${computer.id}`, {
                                method: 'PUT',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({ estado })
                            })
                                .then(response => response.json())
                                .then(data => console.log(data));

                            // Actualizar la información mostrada en el contenedor de información
                            infoContainer.innerHTML = `
                     <p>Total de equipos: ${computerInventoryData.length}</p>
                     <p>Equipos disponibles: ${availableCount}</p>
                     <p>Equipos ocupados: ${occupiedCount}</p>
                     <p>Equipos fuera de servicio: ${outOfServiceCount}</p>
                 `;

                            clickCount = 0; // Reiniciar el contador de clics después del tercer clic
                        }
                    });

                    // Evento mouseover para mostrar información del equipo al pasar el cursor
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

                    // Evento mouseout para ocultar la información al retirar el cursor
                    button.addEventListener('mouseout', () => {
                        const infoPopup = button.querySelector('.info-popup');
                        if (infoPopup) {
                            button.removeChild(infoPopup);
                        }
                    });

                    // Agregar el botón al contenedor de equipos
                    equipmentContainer.appendChild(button);
                });

                // Mostrar la información inicial de los equipos
                infoContainer.innerHTML = `
        <p>Total de equipos: ${computerInventoryData.length}</p>
        <p> <img class="img-1" src="img/computer.png" alt=""> Equipos disponibles: ${availableCount}</p>
        <p> <img class="img-1" src="img/hacker.png" alt=""> Equipos ocupados: ${occupiedCount}</p>
        <p> <img class="img-1" src="img/troubleshooting.png" alt=""> Equipos fuera de servicio: ${outOfServiceCount}</p>
    `;
            });
    });