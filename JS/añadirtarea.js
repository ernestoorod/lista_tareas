async function generarNombreTarea() {
    const apiKey = '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';
    const proxyUrl = 'https://cors-anywhere.herokuapp.com/https://openrouter.ai/api/v1/chat/completions';

    try {
        // Mostrar algún indicador de carga mientras esperamos la respuesta de la API
        document.getElementById('nombretarea').value = 'Generando tarea...'; // Puede ser un texto de carga.
        
        const response = await fetch(proxyUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${apiKey}`
            },
            body: JSON.stringify({
                model: 'deepseek/deepseek-chat:free',
                messages: [
                    {
                        role: "user",
                        content: "Genera una tarea corta, ejemplo comer o dormir. Cambiame la tarea de una respuesta a otra. Sacame solo una tarea."
                    }
                ]
            })
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();

        // Verificar que la respuesta tenga el formato esperado
        if (data && data.choices && data.choices.length > 0 && data.choices[0].message) {
            const taskName = data.choices[0].message.content;

            // Verificar que la tarea generada no esté vacía
            if (taskName && taskName.trim()) {
                console.log(taskName);
                document.getElementById('nombretarea').value = taskName; // Asignar tarea generada al input
            } else {
                alert('La respuesta generada está vacía. Intenta nuevamente.');
            }
        } else {
            alert('No se pudo generar un nombre de tarea. Respuesta inesperada de la API.');
        }
    } catch (error) {
        console.error('Error al generar el nombre de la tarea:', error);
        alert('Hubo un error al generar el nombre de la tarea. Por favor, inténtalo de nuevo. ' + error.message);
    } finally {
        // Restablecer el texto del campo de tarea si la respuesta es vacía
        if (!document.getElementById('nombretarea').value) {
            document.getElementById('nombretarea').value = ''; // Deja el campo vacío si no se generó la tarea
        }
    }
}
