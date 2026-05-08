@include('admin.trainer_packages.form', ['action' => route('trainer-packages.update', $trainerPackage), 'method' => 'PATCH', 'package' => $trainerPackage])
