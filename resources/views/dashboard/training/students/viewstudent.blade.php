@extends('layouts.master')

@section('navbarprim')

    @parent

@stop

@section('content')
    @include('includes.trainingMenu')
    <div class="container" style="margin-top: 20px;">
        <b><h1>Student: {{$student->user->fullName('FLC')}}</h1></b>
        <h4>  @switch ($student->user->rating_short)
          @case('INA')
          Inactive (INA)
          @break
          @case('OBS')
          Pilot/Observer (OBS)
          @break
          @case('S1')
          Ground Controller (S1)
          @break
          @case('S2')
          Tower Controller (S2)
          @break
          @case('S3')
          TMA Controller (S3)
          @break
          @case('C1')
          Enroute Controller (C1)
          @break
          @case('C3')
          Senior Controller (C3)
          @break
          @case('I1')
          Instructor (I1)
          @break
          @case('I3')
          Senior Instructor (I3)
          @break
          @case('SUP')
          Supervisor (SUP)
          @break
          @case('ADM')
          Administrator (ADM)
          @break
          @endswitch</h4>
        <hr>
        <div class="row">
            <div class="col">
                <h3 class="font-weight-bold blue-text pb-2">Training Notes <a class ="btn btn-sm btn-primary"href="{{route('view.add.note', $student->id)}}" style="float: right;">New Training Note</a></h3>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                              <table id="dataTable" class="table table-hover">
                                  <thead>
                                      <tr>
                                          <th scope="col">Title</th>
                                          <th scope="col">Published on</th>
                                          <th scope="col">Published By</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                  @foreach ($student->trainingNotes as $notes)
                                  <tr>
                                      <th scope="row"><a href="{{route('trainingnote.view', $notes->id)}}">{{$notes->title}}</a></th>
                                      <td>
                                        {{$notes->created_at}}
                                      </td>
                                      <td>
                                          <a href="{{route('training.students.view', $student->id)}}">
                                              {{$notes->instructor->user->fullName('FLC')}}
                                          </a>
                                      </td>
                                  </tr>
                                  @endforeach
                              </table>
                        </div>
                    </div>
                  </div>
                </div>
            <br>
            <h3 class="font-weight-bold blue-text pb-2">CBT Progression</h3>
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col">
                    <h5>Modules</h5>
                    Here, you will see what assigned modules are unstarted, in progress and finished.
                  </div>
                  <div class="col">
                    <h5>Exams</h5>
                    Here, you will see results to completed exams, or exams that a student has not begun yet.
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col">
            <h3 class="font-weight-bold blue-text pb-2">Primary Info</h3>
                <div class="card">
                    <div class="card-body">
                        <h5>Training Status</h5>
                        @if ($student->status == 0)
                        <span class="btn btn-sm btn-primary">
                            <h3 class="p-0 m-0">
                                New/Waiting
                            </h3>
                        </span><br></br>
                        The student's training is 'New/Waiting'. This means the student has an accepted application and has not begun training.
                        @elseif ($student->status == 1)
                        <span class="btn btn-sm btn-success">
                            <h3 class="p-0 m-0">
                                In Progress
                            </h3>
                        </span><br></br>
                        The student has an assigned instructor and training is in progress.
                        @elseif ($student->status == 2)
                        <span class="btn btn-sm btn-danger">
                            <h3 class="p-0 m-0">
                                Completed
                            </h3>
                        </span><br></br>
                        The student's training was completed successfully.
                        @else
                        <span class="badge badge-danger">
                            <h3 class="p-0 m-0">
                                Closed
                            </h3>
                        </span><br/>
                        The student's training was closed.
                        @endif
                        <h5 class="mt-3">Assigned Instructor</h5>
                        @if ($student->instructor)
                        <a href="#">
                            {{$student->instructor->user->fullName('FLC')}}
                        </a>
                        @else
                            No instructor assigned
                        @endif
                        <h5 class="mt-3">Application</h5>
                        @if ($student->application != null)
                        Accepted at {{$student->application->processed_at}} by {{\App\Models\Users\User::find($student->application->processed_by)->fullName('FLC')}}
                        @if (Auth::user()->permissions >= 3)
                        <br/>
                        <a href="{{route('training.viewapplication', $student->application->application_id)}}">View application here</a>
                        @endif
                        @endif
                    </div>
                </div>


        <br/>
        <br/>

        <h3 class="font-weight-bold blue-text pb-2">Instructing Sessions</h3>
            <div class="card">
                <div class="card-body">
                    @if (count($student->instructingSessions) >= 1)
                    @else
                    None found!
                    @endif
                </div>
            </div>
            <br><br>

            <h3 class="font-weight-bold blue-text pb-2">Actions</h3>
                <div class="card">
                    <div class="card-body">
                        <h6>Change Status</h6>
                        <form action="{{route('training.students.setstatus', $student->id)}}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col">
                                    <select name="status" required class="custom-select">
                                        <option selected="" value="" hidden>Please choose one..</option>
                                        <option value="1">In Progress</option>
                                        <option value="2">Completed</option>
                                        <option value="0">New/Waiting</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="submit" value="Save" class="btn btn-sm btn-success"></input>
                                </div>
                            </div>
                        </form>
                        <br/>
                        <h6>Instructor</h6>
                        <form action="{{route('training.students.assigninstructor', $student->id)}}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col">
                                    <select name="instructor" required class="custom-select">
                                        <option value="" selected="" hidden>Please choose one..</option>
                                        @foreach ($instructors as $instructor)
                                        <option value="{{$instructor->id}}">{{$instructor->user->fullName('FLC')}}</option>
                                        @endforeach
                                        <option value="unassign">No instructor/unassign</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <input type="submit" value="Save" class="btn btn-sm btn-success"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                </div><br>





<br><br>
      </div>
    </div>
  </div>
{{--
  <div class="modal fade" id="newNote" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
       aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLongTitle">Assign Student to Instructor</h5><br>
              </div>
              <div class="modal-body">
                  <form method="POST" action="{{ route('add.trainingnote') }}" class="form-group">
                      @csrf
                      <label class="form-control">Title</label>
                      <input type="text" name="title" class="form-control"></input>
                          <label class="form-control">Content</label>
                          <textarea name="content" class="form-control"></textarea>
                          <input type="hidden" name="student" value="{{$student->id}}"></input>
                          <input type="submit" class="btn btn-success" value="Add Training Note"></input>
                  </form>
              </div>
              <div class="modal-footer">
                  <button class="btn btn-light" data-dismiss="modal" style="width:375px">Dismiss</button>
              </div>
          </div>
        </div>
      </div> --}}
@stop
