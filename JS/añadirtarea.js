async function generarNombreTarea() {
    const apiKey = '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';
    const url = 'https://openrouter.ai/api/v1';

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${apiKey}`
            },
            body: JSON.stringify({
                model: "deepseek/deepseek-r1:free",
                messages: [
                    {
                        role: "user",
                        content: "Genera un nombre de tarea aleatorio"
                    }
                ]
            })
        });

        if (!response.ok) {
            throw new Error(`Error HTTP: ${response.status} - ${response.statusText}`);
        }

        const data = await response.json();
        if (data && data.choices && data.choices.length > 0) {
            const taskName = data.choices[0].message.content;
            document.getElementById('nombretarea').value = taskName;
        } else {
            alert('No se pudo generar un nombre de tarea. Respuesta inesperada de la API.');
        }
    } catch (error) {
        console.error('Error al generar el nombre de la tarea:', error);
        alert('Hubo un error al generar el nombre de la tarea. Por favor, inténtalo de nuevo. ' + error.message);
    }
}