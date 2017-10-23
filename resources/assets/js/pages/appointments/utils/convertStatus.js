export default function (status) {
    const server = ['pending', 'no_show_patient', 'no_show_doctor', 'general_conflict', 'canceled', 'complete'];
    const client = ['Pending', 'No-Show-Patient', 'No-Show-Doctor', 'General Conflict', 'Canceled', 'Complete'];

    return server.indexOf(status) >= 0
        ? client[server.indexOf(status)]
        : server[client.indexOf(status)];
}
