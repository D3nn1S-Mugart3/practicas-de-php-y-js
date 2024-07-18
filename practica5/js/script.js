fetch('http://localhost/Practica5/php/conexion.php/ComputerInventory')
    .then(response => response.json())
    .then(computerInventoryData => {
        fetch('http://localhost/Practica5/php/conexion.php/ComputerState')
            .then(response => response.json())
            .then(computerStateData => {
                console.log('Computer Inventory Data:', computerInventoryData);
                console.log('Computer State Data:', computerStateData);

                const equipmentContainer = document.getElementById('equipment-container');
                const infoContainer = document.getElementById('info-container');

                let availableCount = 0;
                let outOfServiceCount = 0;

                computerInventoryData.forEach(computer => {
                    const button = document.createElement('button');
                    button.classList.add('equipment-button');

                    // Encontrar el estado correspondiente en ComputerState
                    const state = computerStateData.find(state => state.ci_id === computer.id);
                    const estado = state ? state.estado : 'Fuera de servicio';

                    // Mostrar botón
                    button.innerHTML = `
                                
                            `;
                    // <p><strong>Número PC:</strong> ${computer.numeroPC}</p>
                    // <p><strong>Número de Serie:</strong> ${computer.numeroSerie}</p>
                    // <p><strong>Marca:</strong> ${computer.marca}</p>
                    // <p><strong>Con Mouse:</strong> ${computer.conMouse === '1' ? 'Sí' : 'No'}</p>
                    // <p><strong>Con Teclado:</strong> ${computer.conTeclado === '1' ? 'Sí' : 'No'}</p>
                    button.dataset.id = computer.id;
                    button.style.backgroundColor = estado === 'Disponible' ? 'green' : 'gray';

                    // Agregar evento click para cambiar el estado del botón y actualizar la base de datos
                    button.addEventListener('click', () => {
                        const newState = estado === 'Disponible' ? 'Fuera de servicio' : 'Disponible';
                        const newColor = newState === 'Disponible' ? 'green' : 'gray';
                        button.style.backgroundColor = newColor;

                        // Actualizar estado en la base de datos
                        fetch(`http://localhost/Practica5/php/conexion.php/ComputerState/${computer.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ estado: newState })
                        })
                            .then(response => response.json())
                            .then(data => console.log(data)); // Aquí podrías mostrar un mensaje de éxito

                        // Actualizar recuento de equipos disponibles y fuera de servicio
                        if (newState === 'Disponible') {
                            availableCount++;
                            outOfServiceCount--;
                        } else {
                            availableCount--;
                            outOfServiceCount++;
                        }

                        // Actualizar información debajo de los botones
                        infoContainer.innerHTML = `
                                    <p>Total de equipos: ${computerInventoryData.length}</p>
                                    <p>Equipos disponibles: ${availableCount}</p>
                                    <p>Equipos fuera de servicio: ${outOfServiceCount}</p>
                                `;
                    });

                    // Agregar evento mouseover para mostrar información detallada
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

                    // Agregar evento mouseout para ocultar la información detallada
                    button.addEventListener('mouseout', () => {
                        const infoPopup = button.querySelector('.info-popup');
                        if (infoPopup) {
                            button.removeChild(infoPopup);
                        }
                    });

                    // Agregar botón al contenedor de equipos
                    equipmentContainer.appendChild(button);

                    // Contar equipos disponibles y fuera de servicio
                    if (estado === 'Disponible') {
                        availableCount++;
                    } else {
                        outOfServiceCount++;
                    }
                });

                // Mostrar información debajo de los botones
                infoContainer.innerHTML = `
                            <p>Total de equipos: ${computerInventoryData.length}</p>
                            <p>Equipos disponibles: ${availableCount}</p>
                            <p>Equipos fuera de servicio: ${outOfServiceCount}</p>
                        `;
            });
    });