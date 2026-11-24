require('dotenv').config();
const express = require('express');

const app = express();
const PORT = process.env.PORT || 3000;

//Lee el JSON que llega en el cuerpo de POST y PUT
app.use(express.json());

//Array en memoria de objetos clave-valor con id numérico único
let visitas = [
    {
        id: 1,
        nombre: 'Carmen López',
        email: 'carmen@ejemplo.com',
        telefono: '611223344',
        perro: 'Nieve',
        fecha: '2026-12-10',
        hora: '17:00',
        acompanantes: 0,
        duracion: 60,
        actividad: 'jardin',
        necesidades: ['sentado'],
        comentarios: 'Primera visita'
    }
];

let siguienteId = 2;

//Comprueba que existan los campos obligatorios
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

//GET /api/visitas devuelve todas o filtra por ?texto=...
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

// GET /api/visitas/:id busca un elemento por su id
app.get('/api/visitas/:id', (req, res) => {
    const id = Number(req.params.id);
    for (let i = 0; i < visitas.length; i++) {
        if (visitas[i].id === id) {
        return res.status(200).json(visitas[i]);
        }
    }
    return res.status(404).json({ error: 'Visita no encontrada.' });
});

// POST /api/visitas crea un elemento y responde 201 + cabecera Location
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

//PUT /api/visitas/:id reemplaza el elemento conservando su id
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

app.listen(PORT, () => {
  console.log(`Api escuchando en http://localhost:${PORT}`);
});