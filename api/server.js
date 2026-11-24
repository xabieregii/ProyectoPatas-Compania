require('dotenv').config();
const express = require('express');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());

let visitas = [];
let siguienteId = 1;

function reordenarIds() {
    for (let i = 0; i < visitas.length; i++) {
        visitas[i].id = i + 1;
    }
    siguienteId = visitas.length + 1;
}

function validarVisita(body) {
    if (!body || typeof body !== 'object') {
        return 'No se han recibido datos en formato JSON.';
    }
    const obligatorios = ['nombre', 'email', 'telefono', 'perro', 'fecha', 'hora', 'actividad'];
    for (let i = 0; i < obligatorios.length; i++) {
        const campo = obligatorios[i];
        const valor = body[campo];
        if (valor === undefined || valor === null || String(valor).trim() === '') {
            return `Falta el campo obligatorio: ${campo}.`;
        }
    }
    return null;
}

app.get('/api/visitas', (req, res) => {
    const texto = req.query.texto ? String(req.query.texto).trim().toLowerCase() : '';
    if (!texto) {
        return res.status(200).json(visitas);
    }

    const filtradas = [];
    for (let i = 0; i < visitas.length; i++) {
        const v = visitas[i];
        const textoRegistro = `${v.nombre} ${v.email} ${v.perro} ${v.actividad}`.toLowerCase();
        if (textoRegistro.includes(texto)) {
            filtradas.push(v);
        }
    }
    return res.status(200).json(filtradas);
});

app.get('/api/visitas/:id', (req, res) => {
    const id = Number(req.params.id);
    for (let i = 0; i < visitas.length; i++) {
        if (visitas[i].id === id) {
            return res.status(200).json(visitas[i]);
        }
    }
    return res.status(404).json({ error: 'Visita no encontrada.' });
});

app.post('/api/visitas', (req, res) => {
    const error = validarVisita(req.body);
    if (error) {
        return res.status(400).json({ error });
    }

    const nuevaVisita = {
        id: siguienteId++,
        nombre: String(req.body.nombre).trim(),
        email: String(req.body.email).trim().toLowerCase(),
        telefono: String(req.body.telefono).trim(),
        perro: String(req.body.perro).trim(),
        fecha: String(req.body.fecha).trim(),
        hora: String(req.body.hora).trim(),
        acompanantes: Number(req.body.acompanantes) || 0,
        duracion: Number(req.body.duracion) || 60,
        actividad: String(req.body.actividad).trim(),
        necesidades: Array.isArray(req.body.necesidades) ? req.body.necesidades : [],
        comentarios: req.body.comentarios ? String(req.body.comentarios).trim() : ''
    };

    visitas.push(nuevaVisita);
    res.location(`/api/visitas/${nuevaVisita.id}`);
    return res.status(201).json(nuevaVisita);
});

app.put('/api/visitas/:id', (req, res) => {
    const id = Number(req.params.id);
    let indice = -1;

    for (let i = 0; i < visitas.length; i++) {
        if (visitas[i].id === id) {
            indice = i;
            break;
        }
    }

    if (indice === -1) {
        return res.status(404).json({ error: 'Visita no encontrada.' });
    }

    const error = validarVisita(req.body);
    if (error) {
        return res.status(400).json({ error });
    }

    const actualizada = {
        id: id,
        nombre: String(req.body.nombre).trim(),
        email: String(req.body.email).trim().toLowerCase(),
        telefono: String(req.body.telefono).trim(),
        perro: String(req.body.perro).trim(),
        fecha: String(req.body.fecha).trim(),
        hora: String(req.body.hora).trim(),
        acompanantes: Number(req.body.acompanantes) || 0,
        duracion: Number(req.body.duracion) || 60,
        actividad: String(req.body.actividad).trim(),
        necesidades: Array.isArray(req.body.necesidades) ? req.body.necesidades : [],
        comentarios: req.body.comentarios ? String(req.body.comentarios).trim() : ''
    };

    visitas[indice] = actualizada;
    return res.status(200).json(actualizada);
});

app.delete('/api/visitas/:id', (req, res) => {
    const id = Number(req.params.id);
    let indice = -1;

    for (let i = 0; i < visitas.length; i++) {
        if (visitas[i].id === id) {
            indice = i;
            break;
        }
    }

    if (indice === -1) {
        return res.status(404).json({ error: 'Visita no encontrada.' });
    }

    const eliminada = visitas.splice(indice, 1)[0];
    reordenarIds();
    return res.status(200).json(eliminada);
});

app.listen(PORT, () => {
    console.log(`Api escuchando en http://localhost:${PORT}`);
});