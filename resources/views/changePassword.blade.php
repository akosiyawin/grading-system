@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center" style="height: 100vh">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6 col-sm-12">
                <form id="changePass">
                    <h3 class="display-4" style="font-size: 2rem">Change Password</h3>
                    <input type="password" id="password" class="form-control" placeholder="New Password">
                    <div class="invalid-feedback" id="invalidMessage"></div>
                    <input type="password" id="password_confirmation" class="form-control mt-3" placeholder="Confirm Password">
                    <input type="submit" value="Confirm" class="btn bg-success mt-3">
                    <a type="button" class="btn bg-danger mt-3" href="{{route('handler')}}">Cancel</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        document.getElementById('changePass').addEventListener('submit',(e)=>{
            e.preventDefault()
            document.getElementById('password').classList.remove('is-invalid')
            document.getElementById('invalidMessage').innerText = ''

            const password = document.getElementById('password').value
            const password_confirmation = document.getElementById('password_confirmation').value
            axios.patch('/api/updatePassword',{password,password_confirmation}).then(r=>{
                document.getElementById('password').value = ""
                document.getElementById('password_confirmation').value = ""
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: r.data.message,
                })
            }).catch(err=>{
                document.getElementById('password').classList.add('is-invalid')
                document.getElementById('invalidMessage').innerText = err.response.data.errors.password[0]
            })
        })
    </script>
@endpush